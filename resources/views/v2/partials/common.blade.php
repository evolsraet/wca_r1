@php
  $auctionStatus = Wca::auctionStatus();
  $user = auth()->user();
  if($user){
    $userId = $user?->id;
    $userRole = $user?->hasRole('dealer') ? 'dealer' : 'user';
  }else{
    $userId = null;
    $userRole = null;
  }
@endphp

<script>
  window.auctionStatus = {!! $auctionStatus !!};
  window.userId = '{!! $userId !!}';
  window.userRole = "{!! $userRole !!}";

  function logout() {
    localStorage.removeItem('carInfo');
    localStorage.removeItem('estimatedPrice');

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route('logout') }}';

    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = document.querySelector('meta[name="csrf-token"]').content;
    form.appendChild(csrf);

    document.body.appendChild(form);
    form.submit();
  }
</script>