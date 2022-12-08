(function() {

    function clickMenuClose(e) {
        ao.qs('body').classList.remove('opened');
    }

    function clickMenuOpen(e) {
        ao.qs('body').classList.add('opened');
    }

    function clickRestrictedCheck(e) {
        if(e.ao.target.getAttribute('data-restricted') == 'yes') {
            _ao.error('The requested action is only available to premium accounts. Please upgrade your account to perform this action.', 'Restricted');
            e.preventDefault();
        }
    }

    function clickTool(e) {
        if(e.ao.target.getAttribute('data-restricted') == 'yes') {
            _ao.error('The requested action is only available to premium accounts. Please upgrade your account to perform this action.', 'Restricted');
            return;
        }
        var $article = e.target.closest('article');
        var cls = _ao.action(e) + '_active';

        // If the tool is already open, just close it.
        if($article.classList.contains(cls)) {
            // Eventually use chainable methods
            //_ao.visible('.edit button', $article).click();
            _ao.click(_ao.visible('.edit button', $article));
        } else {
            // Otherwise close the other tools then open this tool.
            _ao.click(_ao.visible('.edit button', $article));

            $article.classList.add(cls);
            $article.classList.add('_active');
        }
    }

    function clickVideo(e) {
        console.log('clickVideo');
        var $a = e.ao.target;
        var url = $a.getAttribute('href');
        // This could be dangerous - trusting Twitter video URL.
        _ao.replaceWith($a, '<video src=' + url + ' controls autoplay loop></video>');
        console.log('complete');
    }

    function closeTool(e) {
        var $el = e.target.closest('._active');
        var actives = [];
        var i;

        if($el) {
            $el.classList.remove('_active');
            for(i = 0; i < $el.classList.length; i++) {
                if($el.classList[i].endsWith('_active')) {
                    $el.classList.remove($el.classList[i]);
                }
            }
        }
    }

    // Need to reset the input contents to the original contents.
    function resetTool(e) {
        console.log('resetTool');
    }

    function submitEdit(e) {
        console.log(e);
        console.log('submitEdit');
    }

    function init() {
        ao.listen('click', 'article .tools button', clickTool);
        ao.listen('click', 'article .edit ._cancel', closeTool, resetTool);
        ao.listen('click', '[data-video]', clickVideo);

        // Check if it is restricted, if not continue and don't prevent default.
        ao.listen('click', 'article ._like [type=submit]', clickRestrictedCheck, ao.continue);

        ao.listen('click', 'header .open', clickMenuOpen);
        ao.listen('click', 'header .close', clickMenuClose);

        ao.listen('success', 'article ._like', _ao.empty);
        
        ao.listen('success', 'article .edit', _ao._toggleSuffixClosest('_active'), _ao._closest('._part'));
    }

    init();

})();
