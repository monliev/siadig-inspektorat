{{-- resources/views/components/alert-popup.blade.php --}}

@if (session('success'))
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Oke'
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        title: 'Gagal!',
        text: "{{ session('error') }}",
        icon: 'error',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oke'
    });
</script>
@endif