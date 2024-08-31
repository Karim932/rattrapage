<!-- Sidebar -->
<aside id="sidebar" class="fixed top-0 left-0 w-64 h-full bg-white shadow-md z-50">
    <div class="h-16 flex items-center justify-between bg-red-500 text-white font-bold text-xl px-4">
      Admin Panel
    </div>
    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center p-4 text-gray-700 hover:bg-red-500 hover:text-white">
            <span class="material-icons">home</span>
            <span class="ml-4">Accueil</span>
        </a>
        <a href="{{ route('users.index') }}" class="flex items-center p-4 text-gray-700 hover:bg-red-500 hover:text-white">
            <span class="material-icons">people</span>
            <span class="ml-4">Gestion des Utilisateurs</span>
        </a>
        <a href="{{ route('adhesion.index') }}" class="flex items-center p-4 text-gray-700 hover:bg-red-500 hover:text-white">
            <span class="material-icons">person_add</span>
            <span class="ml-4">Adhésion des utilisateurs</span>
        </a>
        <a href="{{ route('admin.collectes.index') }}" class="flex items-center p-4 text-gray-700 hover:bg-red-500 hover:text-white">
            <span class="material-icons">local_shipping</span>
            <span class="ml-4">Gestion des collectes</span>
        </a>
        <a href="{{ route('admin.stock.index') }}" class="flex items-center p-4 text-gray-700 hover:bg-red-500 hover:text-white">
            <span class="material-icons">inventory</span>
            <span class="ml-4">Gestion des Stocks</span>
        </a>
        <a href="{{ route('admin.distributions.index') }}" class="flex items-center p-4 text-gray-700 hover:bg-red-500 hover:text-white">
            <span class="material-icons">local_shipping</span>
            <span class="ml-4">Gestion des tournées</span>
        </a>
        <a href="{{ route('services.index') }}" class="flex items-center p-4 text-gray-700 hover:bg-red-500 hover:text-white">
            <span class="material-icons">build</span>
            <span class="ml-4">Gestion des Services</span>
        </a>
        <a href="{{ route('plannings.index') }}" class="flex items-center p-4 text-gray-700 hover:bg-red-500 hover:text-white">
            <span class="material-icons">calendar_today</span>
            <span class="ml-4">Gestion des plannings</span>
        </a>
        <a href="{{ route('annonces.index') }}" class="flex items-center p-4 text-gray-700 hover:bg-red-500 hover:text-white">
            <span class="material-icons">post_add</span>
            <span class="ml-4">Gestion des Annonces</span>
        </a>
        <a href="{{ route('admin.contact.index') }}" class="flex items-center p-4 text-gray-700 hover:bg-red-500 hover:text-white">
            <span class="material-icons">contact_mail</span>
            <span class="ml-4">Contact</span>
        </a>
        <a href="{{ route('services') }}" class="flex items-center p-4 text-gray-700 hover:bg-red-500 hover:text-white">
            <span class="material-icons">arrow_back</span>
            <span class="ml-4">Retour au site</span>
        </a>
    </nav>
  </aside>

<!-- Toggle Button -->
<button id="toggle-button" class="fixed top-16 left-64 bg-red-500 text-white p-2 rounded-r focus:outline-none z-50">
    <span id="toggle-icon" class="material-icons">chevron_left</span>
</button>


