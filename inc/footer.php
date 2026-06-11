</main> 

<footer class="bg-dark text-light py-4 mt-auto border-top border-4 border-warning" style="border-color: var(--junia-orange) !important;">
    <div class="container">
        <div class="row align-items-center py-2">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> <strong>Pote Au Feu</strong>. Tous droits réservés.</p>
                <small class="text-muted">Projet de validation — Architecture Web AP3</small>
            </div>
            
            <div class="col-md-6 text-center text-md-end">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item me-3">
                        <a href="<?php echo $dir; ?>pages/gdpr.php" class="text-decoration-none text-light opacity-75 href-hover">
                            <i class="bi bi-shield-check"></i> Mentions Légales & RGPD
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="<?php echo $dir; ?>pages/contact.php" class="text-decoration-none text-light opacity-75 href-hover">
                            Contact Support
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<style>
    .href-hover:hover {
        color: var(--junia-orange) !important;
        opacity: 1 !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $dir; ?>js/auth.js"></script>
</body>
</html>