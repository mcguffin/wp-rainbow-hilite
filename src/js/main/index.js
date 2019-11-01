import Prism from 'prismjs';
import 'current-script-polyfill';
import 'prismjs/plugins/autoloader/prism-autoloader';

// setup autoloader
Prism.plugins.autoloader.languages_path = document.currentScript.src.replace(/\/([^\/]+)$/,'/prism/components/');

Prism.highlightAll();
