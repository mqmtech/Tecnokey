//-- Anonymous autoload function --//
(function(window){
    /// Namespaces ///
    var app = new Array();
    /// End Namespaces ///

    /// App classes ///
    app['EventSubscriber'] = function(){
        this.listenersManager = new Array();  
        this.addEventListener = function(name, func){
            if(typeof name == 'string' && typeof func == 'function'){
                var listeners = new Array();
                if(this.listenersManager[name] !== undefined){
                    listeners = this.listenersManager[name];
                }
                else{
                    this.listenersManager[name] = listeners;
                }
                listeners.push(func);
            }
        }
        this.dispatchEvent = function(name, event){
            if(typeof name == 'string'){
                if(this.listenersManager[name] !== undefined){
                    var listeners = this.listenersManager[name];
                    for(i in listeners){
                        var listener = listeners[i];
                        listener(event);
                    }
                }
            }
        }
    }
                
    app['Ecommerce'] = function (){
        this.eventSubscriber = new app['EventSubscriber'];
    
        this.test = function(){
            alert('tested');
        }
    }
    /// End App classes ///

    /// App instances ///
    app['ecommerce'] = new app['Ecommerce']();
    /// End App instances ///
    
    /******************************************************************/
    //Set namespaces
    window.tecnokey = (window.tecnokey == null) ? window.tecnokey = new Object() : window.tecnokey;
    //End set namespaces
    
    //Set functions
    window.tecnokey.EventSubscriber = app['EventSubscriber'];
    window.tecnokey.ecommerce = app['ecommerce'];
    //End set functions
    /******************************************************************/
    
})(window)
//-- End Anonymous autoload function --//
    
    
    
