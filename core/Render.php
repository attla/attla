<?php

namespace Attla;

class Render
{
	/**
     * @var Blade
     */
	private $blade;

	/**
	 * Constructor
	 *
	 * Search for the file, if it doesn't exist, call page 404
	 *
	 * @param string $file
	 * @param array $vars
	 * @param boolean $template
	 */
	public function __construct($file, array $vars, $template = true){
		$pFile = VPATH.strtr($file, '/\\', DS.DS);
		if (is_file($pFile.'.blade.php')){
			$this->blade = new Blade(VPATH, VPATH.'cache');

			$this->defineDirectives($template);

			// remove laravel directives
			$this->removeDirectives(['each','inject','can','elsecan','endcan','cannot','elsecannot','endcannot','canany','elsecanany','endcanany','error','enderror','env','elseenv','endenv','unlessenv']);

			echo $this->blade->render($file, $vars);
		}elseif (is_file($pFile.'.php')){
			if ($vars)
				extract($vars, EXTR_REFS | EXTR_SKIP);
			include_once $pFile.'.php';
		}elseif ($template){
			err();
		}
	}

	/**
	 * Define custom blade directives
	 *
	 * @param boolean $template
	 * @return void
	 */
	private function defineDirectives($template){
		if ($template){
			$this->blade->directive('header', function($expression){
				return "<?php h($expression); ?>";
			});

			$this->blade->directive('footer', function($expression){
				return "<?php f($expression); ?>";
			});
		}
		// url
		$this->blade->directive('url', function($expression){
			return "<?php echo uri($expression); ?>";
		});

		$this->blade->directive('uri', function($expression){
			return "<?php echo uri($expression); ?>";
		});
		// assets
		$this->blade->directive('asset', function($expression){
			return "<?php echo assets($expression); ?>";
		});

		$this->blade->directive('assets', function($expression){
			return "<?php echo assets($expression); ?>";
		});
		// globals
		$this->blade->directive('set_global', function($expression){
			return "<?php globals($expression); ?>";
		});

		$this->blade->directive('set_globals', function($expression){
			return "<?php globals($expression); ?>";
		});
		// import
		$this->blade->directive('import', function($expression){
			return "<?php import($expression); ?>";
		});
	}

	/**
	 * Remove blade directives
	 *
	 * @param array $directives
	 * @return void
	 */
	private function removeDirectives(array $directives){
		foreach ($directives as $directive){
			$this->blade->directive($directive, function($expression){
				return '';
			});
		}
	}
}
