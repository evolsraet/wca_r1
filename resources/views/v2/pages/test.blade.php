@extends('v2.layouts.app')

@section('title', 'test')

@section('content')


{{--
<div class="container" x-data="{ message: 'Hello, Alpine.js!' }">
    <h1 x-text="message"></h1>
    <button @click="message = 'You clicked the button!'">Click me</button>
</div>
 --}}
<div x-data="upper()">
    <p>
        상위 :
        <span x-text="like"></span>
    </p>
    <div x-data="lower()">
        <button @click="increment">Click me</button>
        <p>
            하위 :
            <span x-text="like"></span>
        </p>
    </div>
</div>


<script type="text/javascript" defer>
    document.addEventListener('alpine:init', () => {
        Alpine.data('upper', () => ({
            like: 0,
            init() {
                // 리스너 등록
                window.addEventListener('update-auction-like', (e) => {
                    this.like = e.detail;
                });
            }
        }));

        Alpine.data('lower', () => ({
            like: 0,
            increment() {
                this.like++;
                // 이벤트 등록
                this.$dispatch('update-auction-like', this.like);
            }
        }));
    });
</script>

@endsection