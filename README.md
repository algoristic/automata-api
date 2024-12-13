<p align="center">
  <img src="https://algoristic.de/automata/90/126?a=ff0000&ai=0000ff&d=000000">
</p>

<br>

# automata-api

Available on https://algoristic.de/automata/.

Create 2D images of [elementary cellular automata](https://en.wikipedia.org/wiki/Elementary_cellular_automaton) using a simple `GET`-based API.

**Contents**

1. [🚀 Usage Examples](#-usage-examples)
1. [📝 Documentation](#-documentation)
   - [⚗️ Path-Parameters](#️-path-parameters)
   - [🎨 Query-Parameters](#-query-parameters)

<br>
<br>
<br>

## 🚀 Usage Examples

Rule 30 for 256 cells in 256 generations:

> https://algoristic.de/automata/30/256

![Rule 30 for 256 cells in 256 generations](https://algoristic.de/automata/30/256)

---

Rule 30 for 192 cells in 108 generations:

> https://algoristic.de/automata/30/192/108

![Rule 30 for 192 cells in 108 generations](https://algoristic.de/automata/30/192/108)

---

Rule 30 for 192 cells in 108 generations and each cell scaled to occupy 10&times;10 pixels (resulting in a 1920&times;1080 image):

> https://algoristic.de/automata/30/192/108?k=10

![Rule 30 for 192 cells in 108 generations and each cell scaled to occupy 10×10 pixels](https://algoristic.de/automata/30/192/108?k=10)

---

Rule 110 for 256 cells in 256 generations with an ever-changing random start configuration:

> https://algoristic.de/automata/110/256/256/random

![Rule 110 for 256 cells in 256 generations with an ever-changing random start configuration](https://algoristic.de/automata/110/256/256/random)

---

Rule 30 for 256 cells in 256 generations with a fixed seeded start configuration:

> https://algoristic.de/automata/30/256/256/1337

![Rule 30 for 256 cells in 256 generations with a fixed seeded start configuration](https://algoristic.de/automata/30/256/256/1337)

---

Rule 30 for 256 cells in 256 generations and a fixed file name for browser caching:

> https://algoristic.de/automata/30/256/rule_30.webp

![Rule 30 for 256 cells in 256 generations and a fixed file name for browser caching](https://algoristic.de/automata/30/256/rule_30.webp)

---

Rule 30 for 256 cells in 256 generations with living cells in pink and dead cells in turquoise:

> https://algoristic.de/automata/30/256?a=ff00ff&d=00ffff

![Rule 30 for 256 cells in 256 generations with living cells in pink and dead cells in turquoise](https://algoristic.de/automata/30/256?a=ff00ff&d=00ffff)

---

Rule 30 for 256 cells in 256 generations with dead cells in black and a linear interpolation from red to blue for the living cells:

> https://algoristic.de/automata/30/256?a=ff0000&ai=0000ff&d=000000

![Rule 30 for 256 cells in 256 generations with dead cells in black and a linear interpolation from red to blue for the living cells](https://algoristic.de/automata/30/256?a=ff0000&ai=0000ff&d=000000)

<br>
<br>
<br>

## 📝 Documentation

### ⚗️ Path-Parameters

Define what automaton will be calculated.

<table>
  <thead>
    <tr>
      <th colspan="5">Path Segments</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>${rule}</code></td>
      <td><code>${dimensions}</code></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><code>${rule}</code></td>
      <td><code>${dimensions}</code></td>
      <td><code>${filename}.${filetype}</code></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><code>${rule}</code></td>
      <td><code>${size}</code></td>
      <td><code>${generations}</code></td>
      <td><code>${filename}.${filetype}</code></td>
      <td></td>
    </tr>
    <tr>
      <td><code>${rule}</code></td>
      <td><code>${size}</code></td>
      <td><code>${generations}</code></td>
      <td><code>single</code> | <code>random</code> | <code>${seed}</code></td>
      <td></td>
    </tr>
    <tr>
      <td><code>${rule}</code></td>
      <td><code>${size}</code></td>
      <td><code>${generations}</code></td>
      <td><code>single</code> | <code>random</code> | <code>${seed}</code></td>
      <td><code>${filename}.${filetype}</code></td>
    </tr>
  </tbody>
</table>

#### rule

`rule` &rarr; Decimal rule of an elementary cellular automaton

> _0 &le; `rule` &lt; 256_

#### dimensions

`dimensions` &rarr; Shortcut to set `size` and `generations` at once

> _2 &le; `dimensions`_ \
> and \
> _`dimensions` &times; [`scale`](#scale-k) &le; 4096<sup>1</sup>_

#### size

`size` &rarr; Number of cells in a generation

> _2 &le; `size`_ \
> and \
> _`size` &times; [`scale`](#scale-k) &le; 4096<sup>1</sup>_

#### generationas

`generations` &rarr; Number of generations to be evolved

> _1 &le; `generations`_ \
> and \
> _`generations` &times; [`scale`](#scale-k) &le; 4096<sup>1</sup>_

#### filename

`filename` &rarr; Name of the generated image file

> _`filename` has to be a valid file name_

#### filetype

`filetype` &rarr; Mimetype of the generated image file

> _`filetype` &isin; [`png`, `gif`, `jpg`, `jpeg`, `webp`]_

#### 'single' | 'random' | seed

`'single'` | `'random'` &rarr; Start with a _single_ living cell or <i>random</i>ly scattered living cells, seeded with the current datetime

> _`'single'` is the implicit default value_

`seed` &rarr; Start with randomly scattered living cells, seeded with the given value

> _`seed` &isin; &#8469;_

<br>
<br>
<br>

### 🎨 Query-Parameters

Change the size and appearance of the output image.

#### scale, k

`scale`, `k` &rarr; Scaling factor for the printed size of each cell, _default `2`_

> _1 &le; [`scale`, `k`]_ \
> and \
> _[`scale`, `k`] &times; [[`dimensions`](#dimensions), [`size`](#size), [`generations`](#generationas)] &le; 4096_

#### alive, a

`alive`, `a` &rarr; Code for the printing color of live cells, _default `000000`<sup>2</sup>_

> _000000<sub>16</sub> &le; [`alive`, `a`] &le; FFFFFF<sub>16</sub>_

#### alive-interpolate, ai

`alive-interpolate`, `ai` &rarr; Color into which the living cells are interpolated, _default `alive`_

> _000000<sub>16</sub> &le; [`alive-interpolate`, `ai`] &le; FFFFFF<sub>16</sub>_

#### dead, d

`dead`, `d` &rarr; Code for the printing color of dead cells, _default `FFFFFF`<sup>2</sup>_

> _000000<sub>16</sub> &le; [`dead`, `d`] &le; FFFFFF<sub>16</sub>_

#### dead-interpolate, di

`dead-interpolate`, `di` &rarr; Color into which the dead cells are interpolated, _default `dead`_

> _000000<sub>16</sub> &le; [`dead-interpolate`, `di`] &le; FFFFFF<sub>16</sub>_

---

<sup>1</sup> Please note the default value of `2` for [[`scale`, `k`](#scale-k)]

<sup>2</sup> Please note the absence of the `#` charater for the hex values of `alive`, `a`, `dead` and `d` parameters.
