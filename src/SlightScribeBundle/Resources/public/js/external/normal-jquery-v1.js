/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */

function SlightScribeNormalCreateRun(targetSelector, options, theme) {
    this.targetSelector = targetSelector;
    this.options = $.extend({
        // required
        'projectServer':null,
        'projectId':null,
        // optional

    }, options || {});
    if (!this.options.projectServer) {
        this.options.projectServer = 'http://' + document.location.host;
    }
    this.theme = $.extend({
        'loadingHTML':function(data) { return '<div class="loading">Loading, Please Wait</div>'; },
        'bodyHTML':function(data) {
            return '<div class="slightScribeProject">'+
                '<form onsubmit="'+data.submitJavaScript+'">' +
                '<div class="body"></div>'+
                '<div>My email is:<input type="email" name="email"></div>'+
                '<div><input type="submit" value="Start Run"></div>'+
                '</form>'+
                '</div>';
        },
        'savingHTML':function(data) { return '<div class="loading">Saving, Please Wait</div>'; },
        'createdHTML':function(data) {
            var html = '<div>Done</div>';
            if (data.files) {
                html += '<ul>';
                for(idx in data.files) {
                    // TODO html escape!
                    html += '<li><a href="'+data.files[idx].url+'" target="_blank">'+data.files[idx].filename+'</a></li>';
                }
                html += '</ul>';
            }
            return html;
        },
    }, theme || {});
    this.projectData = null;
    this.started = false;
    this.start = function() {
        this.started = true;
        if (this.treeData) {
            this._start();
        } else {
            $(this.targetSelector).html(this.theme.loadingHTML({}));
        }
    };
    this._start = function() {
        $(this.targetSelector).html(this.theme.bodyHTML({'submitJavaScript':this.globalVariableName+'.submit(); return false;'}));
        $(this.targetSelector+ " .slightScribeProject .body").html(this._getFormHTML());
    };
    this._getFormHTML = function() {
        var html = this.projectData.form.replace("\n", '<br/>');
        for(idx in this.projectData.fields) {
            if (this.projectData.fields[idx].type == 'text') {
                html = html.replace('{{' + this.projectData.fields[idx].id + '}}', '<input type="text" name="field_'+this.projectData.fields[idx].id+'">');
            } else if (this.projectData.fields[idx].type == 'textarea') {
                html = html.replace('{{' + this.projectData.fields[idx].id + '}}', '<textarea name="field_'+this.projectData.fields[idx].id+'"></textarea>');
            }
        }
        return html;
    };
    this.submit = function() {
        var formData = $(this.targetSelector+ " .slightScribeProject form").serialize();
        $(this.targetSelector).html(this.theme.savingHTML({}));
        $.ajax({
            context: this,
            data: formData,
            dataType: "jsonp",
            method: "post",
            url: this.options.projectServer + '/app_dev.php/api/v1/project/' + this.options.projectId + '/action.jsonp?callback=?',
            success: function(data) {
                console.log(data);

                $(this.targetSelector).html(this.theme.createdHTML({ 'files': data.files }));


            },
        });

    };
    var globalRefNum = Math.floor(Math.random() * 999999999) + 1;
    while("SlightScribeNormalCreateRun"+globalRefNum in window) {
        globalRefNum = Math.floor(Math.random() * 999999999) + 1;
    }
    window["SlightScribeNormalCreateRun"+globalRefNum] = this;
    this.globalVariableName = "SlightScribeNormalCreateRun"+globalRefNum;
    $.ajax({
        context: this,
        dataType: "jsonp",
        url: this.options.projectServer + '/app_dev.php/api/v1/project/' + this.options.projectId + '/data.jsonp?callback=?',
        success: function(data) {
            console.log(data);
            this.projectData = data;
            if (this.started) {
                this._start();
            }
        },
    });
}
