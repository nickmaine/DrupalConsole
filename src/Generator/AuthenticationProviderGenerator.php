<?php

/**
 * @file
 * Contains \Drupal\Console\Generator\AuthenticationProviderGenerator.
 */

namespace Drupal\Console\Generator;

class AuthenticationProviderGenerator extends Generator
{
    /**
     * Generator Plugin Block.
     *
     * @param $module
     * @param $class
     * @param $provider_id
     */
    public function generate($module, $class, $provider_id)
    {
        $parameters = [
          'module' => $module,
          'class' => $class,
        ];

        $this->renderFile(
            'module/src/Authentication/Provider/authentication-provider.php.twig',
            $this->getSite()->getAuthenticationPath($module, 'Provider').'/'.$class.'.php',
            $parameters
        );

        $parameters = [
          'module' => $module,
          'class' => $class,
          'class_path' => sprintf('Drupal\%s\Authentication\Provider\%s', $module, $class),
          'name' => 'authentication.'.$module,
          'services' => [
            ['name' => 'config.factory'],
            ['name' => 'entity_type.manager'],
          ],
          'file_exists' => file_exists($this->getSite()->getModulePath($module).'/'.$module.'.services.yml'),
          'tags' => [
            'name' => 'authentication_provider',
            'provider_id' => $provider_id,
            'priority' => '100',
          ],
        ];

        $this->renderFile(
            'module/services.yml.twig',
            $this->getSite()->getModulePath($module).'/'.$module.'.services.yml',
            $parameters,
            FILE_APPEND
        );
    }
}
