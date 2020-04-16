/*
	Author	: Michael Janea (https://facebook.com/mbjanea)
	Version	: 2.0
*/
var defX = '14.57836';
var defY = '121.05769';
var defZ = '14';
var defImg = '';
CKEDITOR.dialog.add('locationmapDialog', function(editor) {
    return {
        title: 'Location Map Properties',
        minWidth: 800,
        minHeight: 600,
        contents: [{
            id: 'mapPointing',
            label: 'Map Pointing',
            elements: [{
                type: 'hbox',
                widths: ['50%', '50%'],
                children: [{
                    className: 'latitude',
                    id: 'latitude',
                    label: '* Latitude',
                    type: 'text',
                    validate: CKEDITOR.dialog.validate.notEmpty('Latitude field cannot be empty'),
                    onKeyUp: function(e) {
                        latitude = e.sender.$.value;
                        longitude = document.getElementsByClassName('longitude')[0].getElementsByTagName('input')[0].value;
                        zoom = document.getElementsByClassName('zoom')[0].getElementsByTagName('input')[0].value;
                        img = document.getElementsByClassName('image')[0].getElementsByTagName('input')[0].value;
                        document.getElementById('map').contentWindow.setinitialize(latitude, longitude, zoom, img)
                    },
                    setup: function(widget) {
                        url = widget.data.src;
                        url = url.substring(url.lastIndexOf('?') + 1);
                        url = url.split('&');
                        map_x = url[0].split('=');
                        this.setValue(map_x[1])
                    },
                    commit: function(widget) {
                        widget.setData('latitude', this.getValue())
                    }
                }, {
                    className: 'longitude',
                    id: 'longitude',
                    label: '* Longitude',
                    type: 'text',
                    validate: CKEDITOR.dialog.validate.notEmpty('longitude field cannot be empty'),
                    onKeyUp: function(e) {
                        latitude = document.getElementsByClassName('latitude')[0].getElementsByTagName('input')[0].value;
                        longitude = e.sender.$.value;
                        zoom = document.getElementsByClassName('zoom')[0].getElementsByTagName('input')[0].value;
                        img = document.getElementsByClassName('image')[0].getElementsByTagName('input')[0].value;
                        document.getElementById('map').contentWindow.setinitialize(latitude, longitude, zoom, img)
                    },
                    setup: function(widget) {
                        url = widget.data.src;
                        url = url.substring(url.lastIndexOf('?') + 1);
                        url = url.split('&');
                        map_y = url[1].split('=');
                        this.setValue(map_y[1])
                    },
                    commit: function(widget) {
                        widget.setData('longitude', this.getValue())
                    }
                }]
            }, {
                type: 'hbox',
                widths: ['50%', '50%'],
                children: [{
                    className: 'zoom',
                    id: 'zoom',
                    label: '* Zoom',
                    type: 'text',
                    validate: CKEDITOR.dialog.validate.notEmpty('Zoom field cannot be empty'),
                    onKeyUp: function(e) {
                        latitude = document.getElementsByClassName('latitude')[0].getElementsByTagName('input')[0].value;
                        longitude = document.getElementsByClassName('longitude')[0].getElementsByTagName('input')[0].value;
                        zoom = e.sender.$.value;
                        img = document.getElementsByClassName('image')[0].getElementsByTagName('input')[0].value;
                        document.getElementById('map').contentWindow.setinitialize(latitude, longitude, zoom, img)
                    },
                    setup: function(widget) {
                        url = widget.data.src;
                        url = url.substring(url.lastIndexOf('?') + 1);
                        url = url.split('&');
                        map_z = url[2].split('=');
                        this.setValue(map_z[1])
                    },
                    commit: function(widget) {
                        widget.setData('zoom', this.getValue())
                    }
                }, {
                    type: 'hbox',
                    widths: editor.config.ckfinder ? ['75%', '25%'] : ['100%'],
                    children: [{
                        className: 'image',
                        id: 'image',
                        label: 'Image or Text',
                        type: 'text',
                        setup: function(widget) {
                            url = widget.data.src;
                            url = url.substring(url.lastIndexOf('?') + 1);
                            url = url.split('&');
                            img = url[3].split('=');
                            this.setValue(img[1]);
                            latitude = url[0].split('=');
                            latitude = latitude[1];
                            longitude = url[1].split('=');
                            longitude = longitude[1];
                            zoom = url[2].split('=');
                            zoom = zoom[1];
                            img = img[1] ? '<img src="' + img[1] + '" />' : '';
                            document.getElementById('map').contentWindow.setinitialize(latitude, longitude, zoom, img)
                        },
                        commit: function(widget) {
                            src = editor.config.locationMapPath + 'googlemap.html?x=' + document.getElementsByClassName('latitude')[0].getElementsByTagName('input')[0].value + '&y=' + document.getElementsByClassName('longitude')[0].getElementsByTagName('input')[0].value + '&z=' + document.getElementsByClassName('zoom')[0].getElementsByTagName('input')[0].value + '&img=' + this.getValue() + '&googlemap=true';
                            widget.setData('src', src);
                            widget.setData('frameBorder', '0');
                        }
                    }, {
                        className: 'browse',
                        id: 'browse',
                        label: 'Browse Server',
                        style: 'margin-top:1.35em;' + (!editor.config.ckfinder ? ' display:none;' : ''),
                        type: 'button',
                        onClick: function() {
                            var finder = new CKFinder();
                            finder.callback = function(api) {
                                api.hideTool('f0');
                                api.hideTool('f2')
                            };
                            finder.selectActionFunction = function(fileUrl) {
                                latitude = document.getElementsByClassName('latitude')[0].getElementsByTagName('input')[0].value;
                                longitude = document.getElementsByClassName('longitude')[0].getElementsByTagName('input')[0].value;
                                zoom = document.getElementsByClassName('zoom')[0].getElementsByTagName('input')[0].value;
                                img = fileUrl ? '<img src="' + fileUrl + '" />' : '';
                                document.getElementsByClassName('image')[0].getElementsByTagName('input')[0].value = fileUrl;
                                document.getElementById('map').contentWindow.setinitialize(latitude, longitude, zoom, img)
                            };
                            finder.popup()
                        }
                    }]
                }]
            }, {
                type: 'hbox',
                widths: ['50%', '50%'],
                children: [{
                    id: 'width',
                    label: '* Width',
                    type: 'text',
                    validate: CKEDITOR.dialog.validate.notEmpty('Width field cannot be empty'),
                    setup: function(widget) {
                        this.setValue(widget.data.width)
                    },
                    commit: function(widget) {
                        widget.setData('width', this.getValue())
                    }
                }, {
                    id: 'height',
                    label: '* Height',
                    type: 'text',
                    validate: CKEDITOR.dialog.validate.notEmpty('Height field cannot be empty'),
                    setup: function(widget) {
                        this.setValue(widget.data.height)
                    },
                    commit: function(widget) {
                        widget.setData('height', this.getValue())
                    }
                }]
            }, {
                type: 'html',
                html: '<iframe src="' + CKEDITOR.basePath + 'plugins/locationmap/googlemap.html?x=' + defX + '&y=' + defY + '&z=' + defZ + '&img=' + defImg + '" frameborder="0" id="map" style="width:100%; height:400px; margin:auto;"></iframe>',
                onLoad: function() {
                    var selection = editor.getSelection();
                    element = selection.getStartElement();
                    if (element) element = element.getAscendant('img', true);
                    if (element) {
                        url = decodeURIComponent(element.$.attributes['data-cke-realelement'].nodeValue);
                        el = document.createElement('div');
                        el.innerHTML = url;
                        url = el.getElementsByTagName('iframe')[0].getAttribute('src');
                        url = url.substring(url.lastIndexOf('?') + 1);
                        queryString = url.split('&');
                        x = queryString[0].split('=');
                        map_x = x[1];
                        y = queryString[1].split('=');
                        map_y = y[1];
                        z = queryString[2].split('=');
                        map_z = z[1];
                        if (queryString[3] != undefined) {
                            img = queryString[3].split('=');
                            img = img[1]
                        } else {
                            img = 'Click and then move the icon to your store location'
                        }
                    } else {
                        url = ''
                    }
                    if (!element || element.getName() != 'img' || (parseInt(url.search('googlemap')) == -1)) {
                        element = editor.document.createElement('iframe');
                        insertMode = true
                    } else {
                        insertMode = false
                    }
                    if (!insertMode) document.getElementById('map').setAttribute('src', CKEDITOR.basePath + 'plugins/locationmap/googlemap.html?x=' + map_x + '&y=' + map_y + '&z=' + map_z + '&img=' + img)
                }
            }]
        }]
    }
});