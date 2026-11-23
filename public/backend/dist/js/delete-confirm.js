$(function () {
    $(document).on('submit', '.delete-form', function (e) {
        e.preventDefault();

        const form = this;
        const name = $(form).data('name');

        Swal.fire({
            title: '¿Estás seguro?',
            text: name
                ? 'Se eliminará "' + name + '" definitivamente.'
                : 'Este registro se eliminará definitivamente.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
