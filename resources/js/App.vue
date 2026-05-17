<template>
  <div class="flex h-screen bg-[#f5f4f0] font-sans">

    <!-- Sidebar -->
    <aside class="w-56 bg-[#1a1917] flex flex-col flex-shrink-0">
      <div class="px-6 py-7 border-b border-white/[0.08]">
        <h1 class="font-serif text-[#f0ede6] text-lg leading-tight italic tracking-tight">
          Contratos Laravel
        </h1>
      </div>

      <nav class="p-3 flex-1">
        <p class="text-[10px] text-[#555] uppercase tracking-widest px-3 mb-2">Menu</p>

        <button @click="page = 'clients'" :class="navClass('clients')">
          <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
          </svg>
          Clientes
        </button>

        <button @click="page = 'services'" :class="navClass('services')">
          <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
          </svg>
          Serviços
        </button>

        <button @click="page = 'contracts'" :class="navClass('contracts')">
          <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
          </svg>
          Contratos
          <span v-if="page === 'contracts'" class="ml-auto w-1.5 h-1.5 rounded-full bg-[#c8b89a]" />
        </button>
      </nav>
    </aside>

    <!-- Main -->
    <div class="flex flex-col flex-1 overflow-hidden">

      <!-- Topbar -->
      <header class="h-14 bg-white border-b border-[#e8e5df] px-7 flex items-center justify-between">
        <span class="text-sm font-medium text-[#1a1917]">{{ pageTitle }}</span>
      </header>

      <!-- Page content -->
      <main class="flex-1 overflow-auto p-7">
        <Clients v-if="page === 'clients'" />
        <Services v-if="page === 'services'" />
        <Contracts v-if="page === 'contracts'" />
      </main>

    </div>
  </div>
</template>

<script>
import Clients from './pages/Clients.vue'
import Services from './pages/Services.vue'
import Contracts from './pages/Contracts.vue'

export default {
  components: { Clients, Services, Contracts },
  data() {
    return { page: 'clients' }
  },
  computed: {
    pageTitle() {
      return { clients: 'Clientes', services: 'Serviços', contracts: 'Contratos' }[this.page]
    },
  },
  methods: {
    navClass(p) {
      const base = 'flex items-center gap-2.5 w-full px-3 py-2.5 rounded-lg text-sm transition-all duration-150 text-left border-none cursor-pointer font-sans'
      return this.page === p
        ? `${base} bg-white/10 text-[#f0ede6] font-medium`
        : `${base} bg-transparent text-[#9a9690] hover:bg-white/[0.06] hover:text-[#d6d2cc]`
    },
  },
}
</script>
