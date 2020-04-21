<?php

namespace App\Admin\Fields;

class Select extends Field
{

  protected $getItemsCallback;

  public function init($resource, $parentField = null)
  {
    parent::init($resource, $parentField);
    $this->addOption('items_url', route('admin.api.select-items', [$this->resource->name(), $this->nestedName()]));
  }

  public function items($itemsOrCallback)
  {
    if (is_callable($itemsOrCallback)) {
      $this->addOption('items', null);
      $this->addOption('is-async', true);
      $this->getItemsCallback = $itemsOrCallback;
    } else {
      $this->addOption('items', $itemsOrCallback);
    }
    return $this;
  }

  public function labelBy($labelBy)
  {
    $this->addOption('label_by', $labelBy);
    return $this;
  }

  public function trackBy($trackBy)
  {
    $this->addOption('track_by', $trackBy);
    return $this;
  }

  public function searchable($searchable = true)
  {
    $this->addOption('searchable', $searchable);
    return $this;
  }

  public function getItems($query)
  {
    return is_callable($this->getItemsCallback) ? ($this->getItemsCallback)($query) : null;
  }
}
