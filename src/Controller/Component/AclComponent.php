<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Event\Event;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\Network\Exception\ForbiddenException;

class AclComponent extends Component
{
   public $components = ['Auth'];

   public function initialize(array $config)
   {
      $this->controllerPermissions = $config;
   }

   public function beforeFilter(Event $event)
   {
      $this->controller = $this->_registry->getController();
      $this->allowedActions = $this->Auth->allowedActions;

      if ($this->cannotAccessAnything()) {
         return $this->blockRequest();
      }

      if ($this->canAccessEverything()) {
         return;
      }

      if ($this->methodIsAllowedInAuthComponent()) {
         return;
      }

      if ($this->canAccessWholeController()) {
         return;
      }

      if ($this->methodIsAllowedInAcl()) {
         return;
      }

      return $this->blockRequest();
   }

   private function blockRequest()
   {
      throw new ForbiddenException();
   }

   private function cannotAccessAnything()
   {
      return empty($this->controllerPermissions);
   }

   private function canAccessEverything()
   {
      return isset ($this->controllerPermissions[0]) && $this->controllerPermissions[0] == '*';
   }

   private function methodIsAllowedInAuthComponent()
   {
      return in_array( $this->controller->request->params['action'], $this->allowedActions );
   }

   private function canAccessWholeController()
   {
      return isset($this->controllerPermissions[$this->controller->request->params['controller']]) && $this->controllerPermissions[$this->controller->request->params['controller']] == '*';
   }

   private function methodIsAllowedInAcl()
   {
      return
         isset($this->controllerPermissions[$this->controller->request->params['controller']]) &&
         in_array(
            $this->controller->request->params['action'],
            $this->controllerPermissions[$this->controller->request->params['controller']]
         );
   }
}
