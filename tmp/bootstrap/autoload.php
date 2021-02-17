<?php

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';


// PUT THIS IN bootstrap/autoload.php (at the end)
/** auto-expand DD */
function dd()
{
	array_map(function ($x) {
		(new Illuminate\Support\Debug\Dumper)->dump($x);
	}, func_get_args());
	// Added to auto-expand dd() output
	if (PHP_SAPI !== 'cli') {
		echo "<script>
			function sf_toggle(a, recursive) {
				var s = a.nextSibling || {}, oldClass = s.className, arrow, newClass;
				if ('sf-dump-compact' == oldClass) {
					arrow = '&#9660;';
					newClass = 'sf-dump-expanded';
				} else if ('sf-dump-expanded' == oldClass) {
					arrow = '&#9654;';
					newClass = 'sf-dump-compact';
				} else {
					return false;
				}
				a.lastChild.innerHTML = arrow;
				s.className = newClass;
				if (recursive) {
					try {
						a = s.querySelectorAll('.' + oldClass);
						for (s = 0; s < a.length; ++s) {
							if (a[s].className !== newClass) {
								a[s].className = newClass;
								a[s].previousSibling.lastChild.innerHTML = arrow;
							}
						}
					} catch (e) {}
				}
				return true;
			}
			var tg = document.querySelector('.sf-dump-toggle');
			sf_toggle(tg, true);
			sf_toggle(tg, true);
		</script>";
	}
	die(1);
}
