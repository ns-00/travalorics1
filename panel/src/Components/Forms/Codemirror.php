<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Panel\Components\Forms;

use Illuminate\View\Component;

class Codemirror extends Component
{
    public string $name;

    public string $value;

    public function __construct(string $name, ?string $value)
    {
        $this->name  = $name;
        $this->value = html_entity_decode($value, ENT_QUOTES);
    }

    public function render()
    {
        return view('panel::components.form.codemirror');
    }
}
