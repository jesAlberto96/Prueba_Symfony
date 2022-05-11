var deleteProducto = (route) => {
    if (confirm("¿Está seguro de eliminar este producto?")) {
        window.location.href = route;
	}
}