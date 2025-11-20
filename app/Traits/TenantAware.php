<?php

namespace App\Traits;

trait TenantAware
{
  /**
   * Override the constructor
   */
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setTenantTable();
  }

  /**
   * Set table name berdasarkan tenant
   */
  protected function setTenantTable()
  {
    $suffix = $this->getTenantSuffix();
    $baseTable = $this->getBaseTableName();
    $this->setTable($baseTable . $suffix);
  }

  /**
   * Get tenant suffix dari domain atau session
   */
  public function getTenantSuffix()
  {
    // Opsi 1: Dari config yang di-set per request
    if (config('tenant.suffix')) {
      return config('tenant.suffix');
    }

    // Opsi 2: Dari session
    if (session()->has('lokasi')) {
      return '_' . session()->get('lokasi');
    }

    // Opsi 3: Dari request (middleware akan set ini)
    if (request()->attributes->has('tenant_suffix')) {
      return request()->attributes->get('tenant_suffix');
    }

    // Default
    return '_0';
  }

  /**
   * Get base table name (tanpa suffix)
   */
  protected function getBaseTableName()
  {
    // Jika sudah define di model, gunakan itu
    if (property_exists($this, 'baseTable')) {
      return $this->baseTable;
    }

    // Gunakan nama default Laravel
    return str_replace('\\', '', \Illuminate\Support\Str::snake(\Illuminate\Support\Str::pluralStudly(class_basename($this))));
  }

  /**
   * Override newRelatedInstance untuk relationship
   * Kompatibel dengan Compoships
   */
  public function newRelatedInstance($class)
  {
    // Call parent untuk kompatibilitas dengan trait lain
    $instance = tap(new $class, function ($instance) {
      if (! $instance->getConnectionName()) {
        $instance->setConnection($this->connection);
      }
    });

    return $instance;
  }
}
