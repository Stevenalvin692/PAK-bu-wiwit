        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const menuToggle = document.getElementById('menu-toggle');
    const wrapper = document.getElementById('wrapper');
    if(menuToggle) {
        menuToggle.addEventListener('click', () => {
            wrapper.classList.toggle('toggled');
        });
    }
</script>
</body>
</html>