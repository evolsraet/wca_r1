@php
  $auctionStatus = Wca::auctionStatus();
@endphp

<script>
  window.auctionStatus = {!! $auctionStatus !!};

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