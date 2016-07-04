<?php
namespace Forums\Admin;

class Listener extends \Prefab
{

    public function onSystemRebuildMenu($event)
    {
        if ($model = $event->getArgument('model'))
        {
            $root = $event->getArgument('root');
            $pages = clone $model;
            
            $pages->insert(array(
                'type' => 'admin.nav',
                'priority' => 1000,
                'title' => 'Forums',
                'icon' => 'fa fa-file-text',
                'is_root' => false,
                'tree' => $root,
                'base' => '/admin/forums'
            ));
            
            $children = array(
                array(
                    'title' => 'Posts',
                    'route' => './admin/forums/posts',
                    'icon' => 'fa fa-list'
                ),
                array(
                    'title' => 'Categories',
                    'route' => './admin/forums/categories',
                    'icon' => 'fa fa-folder'
                )
            );
            
            $pages->addChildren($children, $root);
            
            \Dsc\System::instance()->addMessage('Forums added its admin menu items.');
        }
    }

   
}