import Prism from 'prismjs';
import 'current-script-polyfill';
import 'prismjs/plugins/autoloader/prism-autoloader';
import 'prismjs/plugins/line-numbers/prism-line-numbers';

// setup autoloader
Prism.plugins.autoloader.languages_path = document.currentScript.src.replace(/\/([^\/]+)$/,'/prism/components/');

Prism.highlightAll();
