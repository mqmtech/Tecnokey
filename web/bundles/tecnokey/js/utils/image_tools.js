//-- Anonymous autoload function --//
    (function(window){
    
    /**
    * param image img
    * param float width
    * param float height
    **/
    function ajustarImagen(img, width, height){
        //adjust Image width
            var DIV_WIDTH = $(img).parent().width() + 0.0;//298;
            var DIV_HEIGHT = $(img).parent().height() + 0.0;//260;

            var width = width ? width  : $(img).width();
            var height = height ? height  : $(img).height();
            var newWidth = width;
            var newHeight = height;
            
            var vPadding =  0.0 ;
            var hPadding =  0.0 ;
            
            if(width > height){
                newWidth = DIV_WIDTH;
                var proportion = (newWidth / width) + 0.0;
                newHeight = height * proportion;
                //end calc proportion
                
                $(img).width(newWidth + "px");//works in firefox and chrome
                //$(img).attr("width",newWidth);
                
                //let height in auto
                //$(img).height("auto"); //works in firefox and chrome
                $(img).height(newHeight + "px");
                //$(img).attr("height",newHeight);
                //let height in auto
            }
            else{
                newHeight = DIV_HEIGHT;
                var proportion = (newHeight / height) + 0.0;
                newWidth = width * proportion;
                
                //let width in auto
                //$(img).width("auto"); //works in firefox and chrome
                $(img).width(newWidth + "px");
                //$(img).attr("width",newWidth);
                //end let with in auto
                
                $(img).height(newHeight + "px"); ////works in firefox and chrome
                //$(img).attr("height",newHeight);
            }
                hPadding = (DIV_WIDTH - newWidth) / 2.0;
                vPadding = (DIV_HEIGHT - newHeight) / 2.0;
                
                $(img).css("margin-top", vPadding);
                $(img).css("margin-left", hPadding);        
    }
    
    /*
     * Image preview script 
     * powered by jQuery (http://www.jquery.com)
     * 
     * written by Alen Grakalic (http://cssglobe.com)
     * 
     * for more info visit http://cssglobe.com/post/1695/easiest-tooltip-and-image-preview-using-jquery
     * 
     * param image img
     *
     */

    imagePreview = function(img){	
           //CONFIG 

                    xOffset = 10;
                    yOffset = 30;

                    // these 2 variable determine popup's distance from the cursor
                    // you might want to adjust to get the right result

            // END CONFIG 
            $(img).hover(function(e){
                    var src = $(this).find("img").attr("src");
                    //alert(src);
                    //alert(src);
                    this.t = this.title;
                    this.title = "";	
                    var c = (this.t != "") ? "<br/>" + this.t : "";
                    $("body").append("<p id='preview'><img src='"+ src +"' alt='Image preview' />"+ c +"</p>");								 
                    $("#preview")
                            .css("top",(e.pageY - xOffset) + "px")
                            .css("left",(e.pageX + yOffset) + "px")
                            .css("position","absolute")
                            .fadeIn("fast");						
        },
            function(){
                    this.title = this.t;	
                    $("#preview").remove();
        });	
            $(img).mousemove(function(e){
                    $("#preview")
                            .css("top",(e.pageY - xOffset) + "px")
                            .css("left",(e.pageX + yOffset) + "px");
            });			
    }

    /**
     *
     */
    function abrirVentana (pagina, width, height) {
        if(width == null){
            width = 460;
        }
        
        if(height == null){
            height = 400;
        }
            
        var opciones=" status=no, toolbar=no, location=no, directories=no,  menubar=no, scrollbars=no, resizable=no, width=" + width + ", height=" + height + ", top=50, left=150";
        window.open(pagina,"",opciones);
        window.close();
    }
    
    /******************************************************************/
    //Set namespaces
    window.tecnokey = (window.tecnokey == null) ? window.tecnokey = new Object() : window.tecnokey;
    window.tecnokey.utils = (window.tecnokey.utils == null) ? window.tecnokey.utils = new Object() : window.tecnokey.utils;
    //End set namespaces
    
    //Set functions
    window.tecnokey.utils.ajustarImagen = ajustarImagen;
    window.tecnokey.utils.imagePreview = imagePreview;
    window.tecnokey.utils.abrirVentana = abrirVentana;
    //End set functions
    /******************************************************************/
    
    })(window)
    //-- End Anonymous autoload function --//
    
    
    
