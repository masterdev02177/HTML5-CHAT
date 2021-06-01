<!-- Modal -->
<div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
        <h4 class="modal-title">Configuration du chat</h4>
        
      </div>
      <div class="modal-body">
      	<div>
             <div class="checkbox">
                <label for="configPlayNewSoundNewMessage">
                  <input type="checkbox" id="configPlayNewSoundNewMessage" > Jouer un son lors d'un nouveau message
                </label>
          </div>
          
             <div class="checkbox">
                <label for="configPlayNewSoundNewMessagePrivate">
                  <input type="checkbox" id="configPlayNewSoundNewMessagePrivate" checked="checked"> Jouer un son lors d'un nouveau message privé
                </label>
          </div>          
              
             <div class="checkbox">
                <label for="configIgnorNewMessages">
                  <input id="configIgnorNewMessages" type="checkbox"> Ignorer les nouveaux messages privés
                </label>
              </div>   
              
             
             <div class="checkbox">
                <label for="configDisableBuzz">
                  <input id="configDisableBuzz" type="checkbox"> Désactiver le Buzz
                </label>
              </div>     
             <div class="checkbox">
                <label for="configBlockWebcamRequests">
                  <input id="configBlockWebcamRequests" type="checkbox"> Bloquer les demandes de caméra
                </label>
              </div>  
             <div class="checkbox">
                <label for="configPlaySoundNewUserArrives">
                  <input id="configPlaySoundNewUserArrives" type="checkbox">
                   Jouer un son quand un membre rejoint  le salon
               </label>
              </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

      </div>

    </div>
  </div>
</div>
