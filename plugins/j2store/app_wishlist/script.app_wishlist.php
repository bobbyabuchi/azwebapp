<?php
/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
class plgJ2StoreApp_wishlistInstallerScript{

	/**
	 * The list of extra modules and plugins to install on component installation / update and remove on component
	 * uninstallation.
	 *
	 * @var   array
	 */
	protected $modulesSourcePath = 'modules';
	protected $source ='source';
	protected $installation_queue = array(		// modules => { (folder) => { (module) => { (position), (published) } }* }*
			'modules' => array(
					'admin' => array(
							//'foobar' => array('cpanel', 1)
					),
					'site' => array(
							'mod_j2store_wishlist' => array('left', 0),
					)
			));


	function preflight( $type, $parent ) {
		if(!JComponentHelper::isEnabled('com_j2store')) {
			Jerror::raiseWarning(null, 'J2Store not found. Please install J2Store before installing this plugin');
			return false;
		}
		jimport('joomla.filesystem.file');
		$version_file = JPATH_ADMINISTRATOR.'/components/com_j2store/version.php';
		if (JFile::exists ( $version_file )) {
			require_once($version_file);
			if (version_compare ( J2STORE_VERSION, '3.2.11', 'lt' ) ) {
				Jerror::raiseWarning ( null, 'You need at least J2Store version 3.2.11 for this plugin to work' );
				return false;
			}else{

				//here modules will be installed
				$this->installSubextensions($parent);
			}
		} else {
			Jerror::raiseWarning ( null, 'J2Store not found or the version file is not found. Make sure that you have installed J2Store before installing this plugin' );
			return false;
		}
	}


	/**
	 * Installs subextensions (modules, plugins) bundled with the main extension
	 *
	 * @param JInstaller $parent
	 *
	 * @return JObject The subextension installation status
	 */
	protected function installSubextensions($parent)
	{
		$src = $parent->getParent()->getPath('source');

		$db = JFactory::getDbo();

		$status = new JObject();
		$status->modules = array();
		$status->plugins = array();

		// Modules installation
		if (isset($this->installation_queue['modules']) && count($this->installation_queue['modules']))
		{
			foreach ($this->installation_queue['modules'] as $folder => $modules)
			{
				if (count($modules))
				{
					foreach ($modules as $module => $modulePreferences)
					{

						// Install the module
						if (empty($folder))
						{
							$folder = 'site';
						}

						$path = "$src/" . $this->modulesSourcePath . "/$folder/$module";

						if (!is_dir($path))
						{
							$path = "$src/" . $this->modulesSourcePath . "/$folder/mod_$module";
						}

						if (!is_dir($path))
						{
							$path = "$src/" . $this->modulesSourcePath . "/$module";
						}

						if (!is_dir($path))
						{
							$path = "$src/" . $this->modulesSourcePath . "/mod_$module";
						}

						if (!is_dir($path))
						{
							continue;
						}

						// Was the module already installed?
						$sql = $db->getQuery(true)
						->select('COUNT(*)')
						->from('#__modules')
						->where($db->qn('module') . ' = ' . $db->q('mod_' . $module));
						$db->setQuery($sql);

						try
						{
							$count = $db->loadResult();
						}
						catch (Exception $exc)
						{
							$count = 0;
						}

						$installer = new JInstaller;
						$result = $installer->install($path);
						$status->modules[] = array(
								'name'   => 'mod_' . $module,
								'client' => $folder,
								'result' => $result
						);

						// Modify where it's published and its published state
						if (!$count)
						{
							// A. Position and state
							list($modulePosition, $modulePublished) = $modulePreferences;

							$sql = $db->getQuery(true)
							->update($db->qn('#__modules'))
							->set($db->qn('position') . ' = ' . $db->q($modulePosition))
							->where($db->qn('module') . ' = ' . $db->q('mod_' . $module));

							if ($modulePublished)
							{
								$sql->set($db->qn('published') . ' = ' . $db->q('1'));
							}

							$db->setQuery($sql);

							try
							{
								$db->execute();
							}
							catch (Exception $exc)
							{
								// Nothing
							}

							// B. Change the ordering of back-end modules to 1 + max ordering
							if ($folder == 'admin')
							{
								try
								{
									$query = $db->getQuery(true);
									$query->select('MAX(' . $db->qn('ordering') . ')')
									->from($db->qn('#__modules'))
									->where($db->qn('position') . '=' . $db->q($modulePosition));
									$db->setQuery($query);
									$position = $db->loadResult();
									$position++;

									$query = $db->getQuery(true);
									$query->update($db->qn('#__modules'))
									->set($db->qn('ordering') . ' = ' . $db->q($position))
									->where($db->qn('module') . ' = ' . $db->q('mod_' . $module));
									$db->setQuery($query);
									$db->execute();
								}
								catch (Exception $exc)
								{
									// Nothing
								}
							}

							// C. Link to all pages
							try
							{
								$query = $db->getQuery(true);
								$query->select('id')->from($db->qn('#__modules'))
								->where($db->qn('module') . ' = ' . $db->q('mod_' . $module));
								$db->setQuery($query);
								$moduleid = $db->loadResult();

								$query = $db->getQuery(true);
								$query->select('*')->from($db->qn('#__modules_menu'))
								->where($db->qn('moduleid') . ' = ' . $db->q($moduleid));
								$db->setQuery($query);
								$assignments = $db->loadObjectList();
								$isAssigned = !empty($assignments);

								if (!$isAssigned)
								{
									$o = (object)array(
											'moduleid' => $moduleid,
											'menuid'   => 0
									);
									$db->insertObject('#__modules_menu', $o);
								}
							}
							catch (Exception $exc)
							{
								// Nothing
							}
						}
					}
				}
			}
		}
	}



	public function postflight($type, $parent){
		$this->_moveSource($parent);
	}

	/**
	 * Method to move source files into
	 * Products/view
	 * @param object $parent
	 */
	public function _moveSource($parent){
		$src = $parent->getParent()->getPath('source');
		//have to move the files in the path
		$source_path = $src.'/'.$this->source.'/';
		$destination_path = JPATH_SITE.'/components/com_j2store/views/products/tmpl/';
		if (is_dir($source_path)){
			//destination path
			$files = JFolder::files($source_path);
			foreach($files as $file){
				if (!JFile::move($source_path.$file, $destination_path.$file) ) {
					$parent->getParent()->abort('Could not move folder '.$destination_path.'Check permissions.');
					return false;
				}
			}
		}
	}
}
