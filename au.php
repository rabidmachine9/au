<?php
/**
* Plugin Name: au
* Version:4.0
* slug : au,
* Author: K.P.
* description : Plugin description here. Basic HTML allowed.
*/

add_action('admin_menu', 'auto_updated_setup_menu');

function auto_updated_setup_menu(){
  add_menu_page( 'au Page', 'au', 'manage_options', 'test-plugin', 'au_init' );
}

function au_init(){
  $plugin_info = get_file_data(__FILE__, ['version' => 'Version']);
  echo "Plugin Version:". $plugin_info['version'];
  
  $res = execute("git pull origin master");

}


/**
 * Executes a command and reurns an array with exit code, stdout and stderr content
 * @param string $cmd - Command to execute
 * @param string|null $workdir - Default working directory
 * @return string[] - Array with keys: 'code' - exit code, 'out' - stdout, 'err' - stderr
 */
function execute($cmd, $workdir = null) {

  if (is_null($workdir)) {
      $workdir = __DIR__;
  }

  $descriptorspec = array(
     0 => array("pipe", "r"),  // stdin
     1 => array("pipe", "w"),  // stdout
     2 => array("pipe", "w"),  // stderr
  );

  $process = proc_open($cmd, $descriptorspec, $pipes, $workdir, null);

  $stdout = stream_get_contents($pipes[1]);
  fclose($pipes[1]);

  $stderr = stream_get_contents($pipes[2]);
  fclose($pipes[2]);

  return [
      'code' => proc_close($process),
      'out' => trim($stdout),
      'err' => trim($stderr),
  ];
}
?>