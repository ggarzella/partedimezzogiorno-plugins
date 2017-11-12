<?php

class Custom_Date_Add_Hook
{
    protected $actions;

    protected $filters;

    public function __construct() {
        $this->actions = array();
        $this->filters = array();
    }

    public function add_action($hook, $component, $callback, $priority = 10, $number_of_args = 1) {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $number_of_args);
    }

    public function add_filter($hook, $component, $callback, $priority = 10, $number_of_args = 1) {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $number_of_args);
    }

    private function add($hooks, $hook, $component, $callback, $priority, $number_of_args) {
        $hooks[] = array(
            'hook'      => $hook,
            'component' => $component,
            'callback'  => $callback,
            'priority'  => $priority,
            'number_of_args'    => $number_of_args
        );

        return $hooks;

    }

    public function run() {
        foreach ($this->filters as $hook)
            add_filter($hook['hook'], array( $hook['component'], $hook['callback']), $hook['priority'], $hook['number_of_args']);

        foreach ($this->actions as $hook)
            add_action($hook['hook'], array( $hook['component'], $hook['callback']), $hook['priority'], $hook['number_of_args']);
    }
}