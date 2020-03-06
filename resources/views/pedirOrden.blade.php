Negocio : {{ $negocioname }}

<br>
Tu mesa es la n° {{ $mesucha }}

<br>

<hr>

@foreach($cats as $cat)

<strong>{{ $cat->nombre }}</strong>

<br>

@foreach($prods as $prod)

@if($prod->categoria_id == $cat->id)
{{ $prod->nombre }} - {{ $prod->precio }}
<br>

@endif


@endforeach
<hr>

@endforeach
