<template>
  <div class="max-w-4xl">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h2 class="font-serif text-2xl text-[#1a1917] italic font-normal">Serviços</h2>
        <p class="text-xs text-[#aaa] mt-0.5">
          {{ pagination.total ? `${pagination.total} serviço${pagination.total !== 1 ? 's' : ''} cadastrado${pagination.total !== 1 ? 's' : ''}` : 'Gerencie os serviços oferecidos' }}
        </p>
      </div>
      <button
        @click="showForm = !showForm"
        class="flex items-center gap-2 bg-[#1a1917] text-[#f0ede6] text-xs font-medium px-4 py-2 rounded-lg transition-opacity hover:opacity-80 cursor-pointer"
      >
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Novo Serviço
      </button>
    </div>

    <!-- Form -->
    <transition name="slide">
      <div v-if="showForm" class="bg-white border border-[#e8e5df] rounded-xl p-5 mb-6">
        <p class="text-[10px] text-[#aaa] uppercase tracking-widest mb-4 font-medium">
          {{ form.id ? 'Editar Serviço' : 'Novo Serviço' }}
        </p>
        <form @submit.prevent="save" class="grid grid-cols-2 gap-3">
          <input
            v-model="form.name"
            placeholder="Nome do serviço"
            class="h-9 px-3 text-sm bg-[#faf9f7] border border-[#e8e5df] rounded-lg text-[#1a1917] placeholder-[#ccc] focus:outline-none focus:border-[#1a1917] transition-colors"
          />
          <input
            v-model="form.base_price"
            type="number"
            step="0.01"
            min="0"
            placeholder="Preço base (R$)"
            class="h-9 px-3 text-sm bg-[#faf9f7] border border-[#e8e5df] rounded-lg text-[#1a1917] placeholder-[#ccc] focus:outline-none focus:border-[#1a1917] transition-colors"
          />
          <div class="flex gap-2">
            <button
              type="submit"
              class="h-9 px-5 bg-[#1a1917] text-[#f0ede6] text-sm font-medium rounded-lg hover:opacity-80 transition-opacity cursor-pointer"
            >
              Salvar
            </button>
            <button
              type="button"
              @click="reset(); showForm = false"
              class="h-9 px-4 text-sm text-[#999] border border-[#e8e5df] rounded-lg hover:bg-[#faf9f7] transition-colors cursor-pointer"
            >
              Cancelar
            </button>
          </div>
        </form>
      </div>
    </transition>

    <!-- Table -->
    <div class="bg-white border border-[#e8e5df] rounded-xl overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-[#faf9f7] border-b border-[#e8e5df]">
            <th class="text-left text-[10px] text-[#aaa] uppercase tracking-widest font-medium px-4 py-3">Nome</th>
            <th class="text-left text-[10px] text-[#aaa] uppercase tracking-widest font-medium px-4 py-3">Preço base</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="services.length === 0">
            <td colspan="3" class="px-4 py-10 text-center text-sm text-[#ccc]">
              Nenhum serviço cadastrado ainda.
            </td>
          </tr>
          <tr
            v-for="s in services"
            :key="s.id"
            class="border-t border-[#f0ede8] hover:bg-[#faf9f7] transition-colors"
          >
            <td class="px-4 py-3 font-medium text-[#1a1917]">{{ s.name }}</td>
            <td class="px-4 py-3 text-[#888]">R$ {{ Number(s.base_price).toFixed(2) }}</td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2 justify-end">
                <button
                  @click="edit(s)"
                  class="text-xs text-[#888] border border-[#e8e5df] px-3 py-1 rounded-md hover:bg-[#f0ede8] hover:text-[#1a1917] transition-colors cursor-pointer"
                >
                  Editar
                </button>
                <button
                  @click="remove(s.id)"
                  class="text-xs text-[#c0392b] border border-[#fdd] px-3 py-1 rounded-md hover:bg-[#fff0f0] transition-colors cursor-pointer"
                >
                  Excluir
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div
        v-if="pagination.last_page > 1"
        class="flex items-center justify-between px-4 py-3 border-t border-[#e8e5df] bg-[#faf9f7]"
      >
        <p class="text-xs text-[#aaa]">
          Mostrando {{ pagination.from }}–{{ pagination.to }} de {{ pagination.total }}
        </p>
        <div class="flex items-center gap-1">
          <button
            @click="goToPage(pagination.current_page - 1)"
            :disabled="!pagination.prev_page_url"
            class="h-7 w-7 flex items-center justify-center rounded-md border border-[#e8e5df] text-[#888] disabled:opacity-30 disabled:cursor-not-allowed hover:bg-[#f0ede8] transition-colors cursor-pointer"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
          </button>

          <button
            v-for="page in pagination.last_page"
            :key="page"
            @click="goToPage(page)"
            :class="[
              'h-7 w-7 flex items-center justify-center rounded-md text-xs font-medium transition-colors cursor-pointer',
              page === pagination.current_page
                ? 'bg-[#1a1917] text-[#f0ede6]'
                : 'border border-[#e8e5df] text-[#888] hover:bg-[#f0ede8]'
            ]"
          >
            {{ page }}
          </button>

          <button
            @click="goToPage(pagination.current_page + 1)"
            :disabled="!pagination.next_page_url"
            class="h-7 w-7 flex items-center justify-center rounded-md border border-[#e8e5df] text-[#888] disabled:opacity-30 disabled:cursor-not-allowed hover:bg-[#f0ede8] transition-colors cursor-pointer"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import api from "../services/api";
export default {
  data() {
    return {
      services: [],
      showForm: false,
      pagination: {
        current_page: 1,
        last_page: 1,
        from: null,
        to: null,
        total: 0,
        prev_page_url: null,
        next_page_url: null,
      },
      form: { id: null, name: "", base_price: 0 },
    };
  },
  mounted() {
    this.load();
  },
  methods: {
    async load(page = 1) {
      const { data } = await api.get("/v1/services", { params: { page } });
      this.services = data.data;
      this.pagination = {
        current_page:  data.current_page,
        last_page:     data.last_page,
        from:          data.from,
        to:            data.to,
        total:         data.total,
        prev_page_url: data.prev_page_url,
        next_page_url: data.next_page_url,
      };
    },
    goToPage(page) {
      if (page < 1 || page > this.pagination.last_page) return;
      this.load(page);
    },
    async save() {
      if (this.form.id) {
        await api.put(`/v1/services/${this.form.id}`, this.form);
      } else {
        await api.post("/v1/services", this.form);
      }
      this.reset();
      this.showForm = false;
      this.load(this.pagination.current_page);
    },
    edit(item) {
      this.form = { ...item };
      this.showForm = true;
    },
    async remove(id) {
      await api.delete(`/v1/services/${id}`);
      this.load(this.pagination.current_page);
    },
    reset() {
      this.form = { id: null, name: "", base_price: 0 };
    },
  },
};
</script>

<style scoped>
.slide-enter-active, .slide-leave-active { transition: all 0.2s ease; }
.slide-enter-from, .slide-leave-to { opacity: 0; transform: translateY(-6px); }
</style>
