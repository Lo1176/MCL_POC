<?php
 namespace MailPoetVendor\Symfony\Component\DependencyInjection\Loader\Configurator; if (!defined('ABSPATH')) exit; use MailPoetVendor\Symfony\Component\DependencyInjection\Definition; class InstanceofConfigurator extends AbstractServiceConfigurator { use Traits\AutowireTrait; use Traits\BindTrait; use Traits\CallTrait; use Traits\ConfiguratorTrait; use Traits\LazyTrait; use Traits\PropertyTrait; use Traits\PublicTrait; use Traits\ShareTrait; use Traits\TagTrait; public const FACTORY = 'instanceof'; private $path; public function __construct(ServicesConfigurator $parent, Definition $definition, string $id, string $path = null) { parent::__construct($parent, $definition, $id, []); $this->path = $path; } public final function instanceof(string $fqcn) : self { return $this->parent->instanceof($fqcn); } } 