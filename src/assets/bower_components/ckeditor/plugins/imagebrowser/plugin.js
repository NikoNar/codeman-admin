			console.log('imagebrowser')

CKEDITOR.plugins.add('imagebrowser', {
	"init": function (editor) {
		if (typeof(editor.config.imageBrowser_listUrl) === 'undefined' || editor.config.imageBrowser_listUrl === null) {
			console.log('if')
			return;
		}

		var url = editor.plugins.imagebrowser.path + "browser/browser.html?listUrl=" + encodeURIComponent(editor.config.imageBrowser_listUrl);
		if (editor.config.baseHref) {
			console.log('second if')

			url += "&baseHref=" + encodeURIComponent(editor.config.baseHref);
		}

		editor.config.filebrowserImageBrowseUrl = url;
	}
});
