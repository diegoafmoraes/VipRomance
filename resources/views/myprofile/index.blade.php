{{-- Tabs --}}
<div class="bg-white rounded-2xl shadow p-5">
  <div class="flex flex-wrap gap-2 border-b border-gray-100 pb-3">
    <button type="button" class="tab-btn px-4 py-2 rounded-full text-sm font-bold bg-rose-500 text-white" data-tab="perfil">
      Meu Perfil
    </button>
    <button type="button" class="tab-btn px-4 py-2 rounded-full text-sm font-bold bg-gray-100 text-gray-700 hover:bg-gray-200" data-tab="fotos">
      Minhas fotos
    </button>
    <button type="button" class="tab-btn px-4 py-2 rounded-full text-sm font-bold bg-gray-100 text-gray-700 hover:bg-gray-200" data-tab="prefs">
      Preferências/Características
    </button>
  </div>

  <div class="mt-5">
    <section class="tab-panel" data-panel="perfil">
      {{-- ✅ aqui você cola o conteúdo já pronto do “Meu Perfil” --}}
      @include('myprofile.partials.meu-perfil')
    </section>

    <section class="tab-panel hidden" data-panel="fotos">
      {{-- ✅ aqui você cola o conteúdo já pronto de “Minhas fotos” --}}
      @include('myprofile.partials.minhas-fotos')
    </section>

    <section class="tab-panel hidden" data-panel="prefs">
      {{-- ✅ aqui entra o que vamos construir --}}
      @include('myprofile.partials.preferencias')
    </section>
  </div>
</div>

<script>
(function(){
  const btns = document.querySelectorAll('.tab-btn');
  const panels = document.querySelectorAll('.tab-panel');

  function setTab(key){
    panels.forEach(p => p.classList.toggle('hidden', p.dataset.panel !== key));
    btns.forEach(b => {
      const active = b.dataset.tab === key;
      b.classList.toggle('bg-rose-500', active);
      b.classList.toggle('text-white', active);
      b.classList.toggle('bg-gray-100', !active);
      b.classList.toggle('text-gray-700', !active);
    });
  }

  btns.forEach(b => b.addEventListener('click', () => setTab(b.dataset.tab)));
})();
</script>