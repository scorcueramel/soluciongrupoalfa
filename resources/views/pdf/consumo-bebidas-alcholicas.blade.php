@if(is_null($consumoBebidasAlcoholicas))
  <p>Mencionó que no consume bebidas alcohólicas</p>
@else
  <p>Mencionó que consume bebidas alcohólicas {{ $consumoBebidasAlcoholicas }}</p>
@endif
