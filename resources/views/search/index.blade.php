{{-- resources/views/app.blade.php --}}

@extends('app')

@section('content')
    <div class="container py-4">
        <h1>Resultados de búsqueda</h1>

        @if ($q)
            <p>Buscaste: <strong>{{ $q }}</strong></p>
        @endif

        @if ($products->count())
            <div class="row">
                @foreach ($products as $product)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            @if (!empty($product->image))
                                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            @endif

                            <div class="card-body">
                                <h5>{{ $product->name }}</h5>
                                <p>{{ Str::limit($product->description, 80) }}</p>
                                <p><strong>S/ {{ number_format($product->price, 2) }}</strong></p>
                                <a href="{{ url('/producto/' . $product->id) }}" class="btn btn-dark">Ver producto</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $products->links() }}
        @else
            <p>No se encontraron productos.</p>
        @endif
    </div>
@endsection
