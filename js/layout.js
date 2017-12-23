
/*


.mslide {
    padding:5px;
}

.mcontent {
    height: 250px; border: solid 0x red; text-align: justify;
}


.mcell { 
    position:relative; display:inline-block; width: 177px; height: 250px; vertical-align:top; border: solid 1px red; 
}


*/

var MLayout = function(options) {

    var defaults = {
        row:1,
        column:4,
        container_width: 500,
        container_height: 500,
        item_width: 50,
        item_height: 50,
        margin: 5,
        item_aspect_ratio: .74, 

/*
        scale: false,
        li_class : 'slide',
        slide_class : 'mcontent',
        item_class : 'mcell',
        click_callback : '',
*/
        action : (function(id) {console.log(id);}),
        items: []
    };

    var settings = $.extend(defaults, options);

    var nItems = settings.items.length;

    var row = settings.row;
    var column = settings.column;

    if (row==0) {
        row = Math.floor(settings.container_height/settings.item_height);
        if (row==0) row=1;
    }
    if (column==0) {
        column = Math.floor(settings.container_width/(settings.item_width+settings.margin*2));
        if (column==0) column=4;
    }

    var perPage = row * column;

    var nPages = Math.floor((nItems-1)/perPage+1);

    var html_content='<ul class="slides" style="border:solid 0px red;">';

    var remain=nItems;
    var i=0;

    var hasCheckFunction = (typeof(download_ifDownloaded) == "function")? 1:0;

    var cell_width = (settings.container_width/column);
    var cell_height = (settings.container_height/row);

    var mark_top=0;
    if (cell_width/cell_height < settings.item_aspect_ratio) {
        mark_top =(cell_height-cell_width/settings.item_aspect_ratio)/2;
    }

    while (remain>0) {
        
        //html_content += '<li class="' + settings.li_class+ '">';
        html_content += '<li>';
        count =  (remain>perPage)? perPage:remain;

        //html_content += '<div class="'+ settings.slide_class + '" style="border:solid 1px red; width:'+settings.container_width+'px;height:'+settings.container_height+'px;" >';
        html_content += '<div style="display:table; table-layout: fixed; border-spacing:'+settings.margin+'px; border: 0px; width:'+settings.container_width+'px;height:'+settings.container_height+'px;" >';

        tag_open = false;
        for (j=0; j<count; j++) {
 
            if (j%column==0) {
                if (tag_open) html_content += '</div>';
                html_content += '<div style="display:table-row; text-align:right;">'+"\n";
                tag_open = true;
            }
         
            disp = (hasCheckFunction && download_ifDownloaded(settings.items[i].id)>=0)?"inline-block":"none";  

            mark= '<img src="images/downloads.png" id="downloaded_icon_'+settings.items[i].id+'" class="downloaded_icon" data-id="'+settings.items[i].id +'" style="position:relative; width:31px; height:31px; right:0px; top: '+ mark_top +'px; display:'+disp+'; z-index:100;"/>';
//            if (parent.ifDownloaded(id)!=-1)mark='<img src="images/downloads.png" style="position:absolute; width:31px; height:31px; right:5px; top: -5px; z-index:100;" />';
            callback = (settings.click_callback!='')?'onClick="'+settings.click_callback+'(\''+settings.items[i].id+'\');" ' : '';

            //cell = '<div class="' +settings.item_class+'" style="float:left; width: '+settings.item_width+ 'px; height: ' + settings.item_height+ 'px; background-image:url('+settings.items[i].url+'); background-repeat: no-repeat; background-position: center center;background-size:center; cursor:pointer; " ' + callback + ' >'+mark+ '</div> ';
            //cell = '<div style="display:table-cell; width: '+settings.item_width+ 'px; height: ' + settings.item_height+ 'px; background-image:url('+settings.items[i].url+'); background-repeat: no-repeat; background-position: center center;background-size:center; cursor:pointer; " ' + callback + ' >'+mark+ '</div> ';
            cell = '<div style="display:table-cell; background-image:url('+settings.items[i].url+'); background-repeat: no-repeat; background-position: center center; background-size:contain; cursor:pointer; " ' + callback + ' >'+mark+'</div> ';
            html_content+=cell;

            i++;
        }

        for (j=count; j<perPage; j++) {
            if (j%column==0) {
                if (tag_open) html_content += '</div>'
                html_content += '<div style="display:table-row; text-align:center;">'+"\n";
                tag_open = true;
            }
            cell = '<div style="display:table-cell;"> &nbsp;</div> ';
            html_content+=cell;

        }
        if (tag_open) html_content += '</div>';
        tag_open=false;
        html_content +="</div></li>";
/*
        for (j=count; j<perPage; j++) {
            cell = '<div class="' +settings.item_class+'" style="width: '+settings.item_width+ 'px; height: ' + settings.item_height+ 'px; "></div> ';
            html_content+=cell;
        }
        html_content +="<span class='stretch'> </span></div></li>";
*/

        remain -=count;
    }

    html_content += '</ul>';

    return html_content;
};

