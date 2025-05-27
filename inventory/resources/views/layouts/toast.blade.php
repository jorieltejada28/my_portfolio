@if (session('success') || session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "{{ session('success') ? 'success' : 'error' }}",
                title: @json(session('success') ?? session('error')),
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: "{{ session('success') ? '#4CAF50' : '#f44336' }}",
                color: "#FFFFFF",
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
        });
    </script>
@endif
