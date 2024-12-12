<?php

class Automaton
{
    public $grid = [];
    private int $rule;
    private int $size;

    function __construct(int $rule, int $size, string $start)
    {
        $this->rule = $rule;
        $this->size = $size;
        $this->init($start);
    }

    function evolve(int $generations): void
    {
        for ($gen = 1; $gen < $generations; $gen++) {
            $parents = $this->grid[$gen - 1];
            $children = [];

            $l_0 = $parents[count($parents) - 1];
            $c_0 = $parents[0];
            $r_0 = $parents[1];
            array_push(
                $children,
                $this->next_state($l_0, $c_0, $r_0)
            );

            for ($i = 1; $i < $this->size - 1; $i++) {
                $l_i = $parents[$i - 1];
                $c_i = $parents[$i];
                $r_i = $parents[$i + 1];
                array_push(
                    $children,
                    $this->next_state($l_i, $c_i, $r_i)
                );
            }

            $l_n = $parents[count($parents) - 2];
            $c_n = $parents[count($parents) - 1];
            $r_n = $parents[0];
            array_push(
                $children,
                $this->next_state($l_n, $c_n, $r_n)
            );
            array_push($this->grid, $children);
        }
    }

    private function init(string $start): void
    {
        $first_generation = [];
        $mid = intval($this->size / 2);
        for ($i = 0; $i < $this->size; $i++) {
            $state = 0;
            if ($start === 'random') {
                $state = $this->get_random_state();
            }
            if ($start === 'single' && $i === $mid) {
                $state = 1;
            }
            array_push($first_generation, $state);
        }
        array_push($this->grid, $first_generation);
    }

    private function next_state(int $l, int $c, int $r): int
    {
        $idx = ($l << 2) | ($c << 1) | $r;
        $next_state = $this->rule >> $idx & 1;
        return $next_state;
    }

    private function get_random_state(): int
    {
        return rand(0, 1);
    }
}