/**
 * Adds lines and numbers to the code element by adding a span.line at each newline.
 * Set the starting line number by adding data-line="234" attribute to code element.
 * Disable line numbering by setting data-line="-1"
 * Each span.line has an id so you can easily jump to a specific line using and anchor href like #rb1ln30 (meaning rainbow block 1 line 30)
 * @summary Line numbering for Rainbow.js
 * @version 1.1.2
 * @author Ron Valstar (http://www.sjeiti.com/)
 * @namespace Rainbow.linenumbers
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @requires Rainbow.js
 */
if (window.Rainbow&&!window.Rainbow.linenumbers) window.Rainbow.linenumbers = (function(Rainbow){
	var iCodeElement = 0
		,mGenericLineStyle = document.createElement('style')
	;
	// add generic .line style
	mGenericLineStyle.appendChild(document.createTextNode('pre code.rainbow .line { position: relative; padding-right: 10px; }'
		+'pre code.rainbow .line:before{ content: attr(data-line); display: inline-block; text-align: right; white-space: nowrap; }'
		+'pre code.rainbow .line:after{ content:\'\'; position: absolute; left: 0; bottom: 0; }'));
	document.head.appendChild(mGenericLineStyle);
	//
	// handle each code block
	Rainbow.onHighlight(addLines);

	/**
	 * Add lines to a <code> element
	 * @param {HTMLElement} codeElement
	 */
	function addLines(codeElement){
		iCodeElement++;
		var rxLineMatch = /\r\n|\r|\n/g
			,iLines = codeElement.innerHTML.replace(rxLineMatch,"\n").split("\n").length
			,iLineStart = codeElement.getAttribute('data-line')<<0||1
			,bAddLineNumbering = iLineStart>=0
			,sBlockId = 'rb'+iCodeElement
			,mParent = codeElement.parentNode // pre
			//
			,iCharWidth = calculateCharacterWidth(codeElement)
			,iLineBlockWidth = 1 + String(iLineStart+iLines-1).length*iCharWidth
			//
			,iLine
			,sBlock
			//
			,mStyle
		;
		if (bAddLineNumbering) {
			iLine = iLineStart;
			sBlock = getLine(sBlockId,iLineStart)+codeElement.innerHTML.replace(rxLineMatch,function(match){
				return match+getLine(sBlockId,++iLine);
			});
			//
			// add class to block
			codeElement.classList.add(sBlockId);
			if (getStyle(codeElement).display==='block') {
				window.addEventListener('resize', handleResize, false);
			}
			//
			// add style element
			mStyle = document.createElement('style');
			handleResize();
			mParent.parentNode.insertBefore(mStyle, mParent);
			//
			// set block html
			codeElement.innerHTML = sBlock;
		}
		function handleResize(){
			setBlockStyle(mStyle,sBlockId,iLineBlockWidth,codeElement.offsetWidth);
		}
	}

	/**
	 * Add line numbers as <span id="rb1ln32"></span> to be able to link to a specific line.
	 * @param {string} blockID
	 * @param {number} nr
	 * @returns {string}
	 */
	function getLine(blockID,nr){
		var sId = blockID+'ln'+nr;
		return '<span id="'+sId+'" class="line" data-line="'+nr+'"></span>';
	}

	/**
	 *
	 * @param {HTMLElement} elm
	 * @param {string} blockID
	 * @param {number} lineBlockWidth
	 * @param {number} lineWidth
	 */
	function setBlockStyle(elm,blockID,lineBlockWidth,lineWidth) {
		elm.textContent = 'pre code.rainbow.'+blockID+' .line:before{ width: '+lineBlockWidth+'px; }'
			+'pre code.rainbow.'+blockID+' .line:after{ width:'+(lineWidth||0)+'px }';
	}

	/**
	 * Calculate character width to determine the size of the .line element.
	 * @param {HTMLElement} elm
	 * @returns {number}
	 */
	function calculateCharacterWidth(elm){
		var iTestExp = 5
			,mTestDiv = document.createElement('div')
			,oTestStyle = mTestDiv.style
			,oCodeStyle = getStyle(elm)
			,oTestCSS = {font:oCodeStyle.font,width:'auto',display:'inline-block'}
			,iReturnWidth
		;
		mTestDiv.appendChild(document.createTextNode(new Array(1<<iTestExp).join('a')+'a'));
		for (var s in oTestCSS) oTestStyle[s] = oTestCSS[s];
		document.body.appendChild(mTestDiv);
		iReturnWidth = mTestDiv.offsetWidth>>iTestExp;
		document.body.removeChild(mTestDiv);
		return iReturnWidth;
	}

	/**
	 * Get the style of an element.
	 * @param {HTMLElement} elm
	 * @returns {IEElementStyle|DocumentView|CssStyle|CSSStyleDeclaration}
	 */
	function getStyle(elm){
		return elm.currentStyle||(document.defaultView&&document.defaultView.getComputedStyle(elm,null))||elm.style;
	}

	// expose main method te be able to be called manually
	return addLines;
})(window.Rainbow);