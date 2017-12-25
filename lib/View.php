<?php
namespace Lib;
use Lib\exceptions\ViewException;

class View{

	static function make($view, array $data=[])
	{
		$app = require "../config/app.php";
		$app_template_engine = $app['template_engine'];
		$app_layout_path = $app['layout_path'];

		$path = str_replace('.', DIRECTORY_SEPARATOR, $view);
		$path_to_include = '..'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.$path.'.php';

		if(!is_file($path_to_include))
		{
			echo '<pre>';
			throw new ViewException("View '$path' not found.");
		}

		extract($data);
		if($app_template_engine === false)
		{
			if(!is_file($app_layout_path))
			{
				echo '<pre>';
				throw new \Exception("Layout file for template is missing at location '$app_layout_path'", 1);
			}

			ob_start();
			include $app_layout_path;
			$layout = ob_get_clean();
			echo $layout;
			return; 
		}
		else
		{
			ob_start();
			include $path_to_include;
			$page = ob_get_clean();
			echo $page;
			return;
		}
	}
}