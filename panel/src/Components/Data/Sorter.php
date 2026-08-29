<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Panel\Components\Data;

use Illuminate\View\Component;

class Sorter extends Component
{
    /**
     * Available sort options
     *
     * @var array
     */
    public array $options;

    /**
     * Create a new component instance.
     *
     * @param  array  $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('panel::components.data.sorter');
    }
}
