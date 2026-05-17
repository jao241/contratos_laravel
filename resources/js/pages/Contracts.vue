<template>
    <div class="max-w-4xl">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-serif text-2xl text-[#1a1917] italic font-normal">Contratos</h2>
                <p class="text-xs text-[#aaa] mt-0.5">
                    {{ pagination.total ? `${pagination.total} contrato${pagination.total !== 1 ? 's' : ''}
                    cadastrado${pagination.total !== 1 ? 's' : ''}` : 'Crie e gerencie contratos de serviço' }}
                </p>
            </div>
            <button @click="openForm()"
                class="flex items-center gap-2 bg-[#1a1917] text-[#f0ede6] text-xs font-medium px-4 py-2 rounded-lg transition-opacity hover:opacity-80 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Novo Contrato
            </button>
        </div>

        <!-- Form -->
        <transition name="slide">
            <div v-if="showForm" class="bg-white border border-[#e8e5df] rounded-xl p-5 mb-6 space-y-5">
                <p class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium">
                    {{ form.id ? 'Editar Contrato' : 'Novo Contrato' }}
                </p>

                <!-- Dados básicos -->
                <div class="grid grid-cols-2 gap-3">
                    <select v-model="form.client_id"
                        class="col-span-2 h-9 px-3 text-sm bg-[#faf9f7] border border-[#e8e5df] rounded-lg text-[#1a1917] focus:outline-none focus:border-[#1a1917] transition-colors">
                        <option value="">Selecione um cliente</option>
                        <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }}</option>
                    </select>
                    <div>
                        <label class="text-[10px] text-[#aaa] uppercase tracking-widest block mb-1">Início</label>
                        <input v-model="form.start_date" type="date"
                            class="w-full h-9 px-3 text-sm bg-[#faf9f7] border border-[#e8e5df] rounded-lg text-[#1a1917] focus:outline-none focus:border-[#1a1917] transition-colors" />
                    </div>
                    <div>
                        <label class="text-[10px] text-[#aaa] uppercase tracking-widest block mb-1">Fim</label>
                        <input v-model="form.end_date" type="date"
                            class="w-full h-9 px-3 text-sm bg-[#faf9f7] border border-[#e8e5df] rounded-lg text-[#1a1917] focus:outline-none focus:border-[#1a1917] transition-colors" />
                    </div>
                    <div>
                        <label class="text-[10px] text-[#aaa] uppercase tracking-widest block mb-1">Status</label>
                        <select v-model="form.status"
                            class="h-9 px-3 text-sm bg-[#faf9f7] border border-[#e8e5df] rounded-lg text-[#1a1917] focus:outline-none focus:border-[#1a1917] transition-colors">
                            <option value="active">Ativo</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                    </div>
                </div>

                <!-- Serviços disponíveis -->
                <div>
                    <p class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium mb-3">Adicionar Serviços</p>
                    <div class="border border-[#e8e5df] rounded-lg overflow-hidden">
                        <div v-for="s in services" :key="s.id"
                            class="flex items-center gap-3 px-4 py-3 border-b border-[#f0ede8] last:border-b-0 hover:bg-[#faf9f7] transition-colors">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-[#1a1917] truncate">{{ s.name }}</p>
                                <p class="text-xs text-[#aaa]">R$ {{ Number(s.base_price).toFixed(2) }}</p>
                            </div>
                            <input type="number" v-model.number="s.quantity" min="1" placeholder="Qtd"
                                class="w-16 h-8 px-2 text-sm text-center bg-[#faf9f7] border border-[#e8e5df] rounded-lg focus:outline-none focus:border-[#1a1917] transition-colors" />
                            <button @click="addItem(s)"
                                class="h-8 px-3 text-xs font-medium bg-[#f0fdf4] text-[#166534] border border-[#bbf7d0] rounded-lg hover:bg-[#dcfce7] transition-colors cursor-pointer">
                                + Adicionar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Novos itens pendentes -->
                <div v-if="items.length > 0">
                    <p class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium mb-3">Novos itens a
                        adicionar</p>
                    <div class="border border-[#e8e5df] rounded-lg overflow-hidden">
                        <div v-for="(item, index) in items" :key="index"
                            class="flex items-center gap-3 px-4 py-3 border-b border-[#f0ede8] last:border-b-0">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-[#1a1917]">{{ item.name }}</p>
                                <p class="text-xs text-[#aaa]">{{ item.quantity }} × R$ {{
                                    Number(item.unit_price).toFixed(2) }}</p>
                            </div>
                            <p class="text-sm font-medium text-[#1a1917]">
                                R$ {{ (item.quantity * item.unit_price).toFixed(2) }}
                            </p>
                            <button @click="removeItem(index)"
                                class="text-xs text-[#c0392b] border border-[#fdd] px-2 py-1 rounded-md hover:bg-[#fff0f0] transition-colors cursor-pointer">
                                Remover
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Itens já salvos (edição) -->
                <div v-if="form.id && existingItems.length > 0">
                    <p class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium mb-3">Itens do contrato</p>
                    <div class="border border-[#e8e5df] rounded-lg overflow-hidden">
                        <div class="bg-[#faf9f7] border-b border-[#e8e5df] grid grid-cols-4 px-4 py-2">
                            <span class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium">Serviço</span>
                            <span
                                class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium text-center">Qtd</span>
                            <span
                                class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium text-center">Preço
                                unit.</span>
                            <span
                                class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium text-right">Subtotal</span>
                        </div>
                        <div v-for="item in existingItems" :key="item.id"
                            class="grid grid-cols-4 px-4 py-3 border-b border-[#f0ede8] last:border-b-0 items-center">
                            <p class="text-sm font-medium text-[#1a1917]">{{ item.service?.name }}</p>
                            <p class="text-sm text-[#888] text-center">{{ item.quantity }}</p>
                            <p class="text-sm text-[#888] text-center">R$ {{ Number(item.unit_price).toFixed(2) }}</p>
                            <div class="flex items-center justify-end gap-2">
                                <p class="text-sm font-medium text-[#1a1917]">
                                    R$ {{ (item.quantity * item.unit_price).toFixed(2) }}
                                </p>
                                <button @click="removeExistingItem(item.id)"
                                    class="text-xs text-[#c0392b] border border-[#fdd] px-2 py-1 rounded-md hover:bg-[#fff0f0] transition-colors cursor-pointer">
                                    Remover
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total estimado -->
                <div v-if="items.length > 0 || existingItems.length > 0"
                    class="flex items-center justify-between px-4 py-3 bg-[#faf9f7] rounded-lg border border-[#e8e5df]">
                    <span class="text-xs text-[#aaa] uppercase tracking-widest font-medium">Total estimado</span>
                    <span class="font-serif text-xl italic text-[#1a1917]">R$ {{ grandTotal.toFixed(2) }}</span>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 pt-1">
                    <button @click="save"
                        class="h-9 px-5 bg-[#1a1917] text-[#f0ede6] text-sm font-medium rounded-lg hover:opacity-80 transition-opacity cursor-pointer">
                        Salvar Contrato
                    </button>
                    <button @click="reset(); showForm = false"
                        class="h-9 px-4 text-sm text-[#999] border border-[#e8e5df] rounded-lg hover:bg-[#faf9f7] transition-colors cursor-pointer">
                        Cancelar
                    </button>
                </div>
            </div>
        </transition>

        <!-- Items panel -->
        <transition name="slide">
            <div v-if="showItems" class="bg-white border border-[#e8e5df] rounded-xl p-5 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium">
                        Itens — {{ selectedContract?.client?.name }}
                    </p>
                    <button @click="showItems = false; selectedContract = null"
                        class="text-xs text-[#999] border border-[#e8e5df] px-3 py-1 rounded-md hover:bg-[#faf9f7] transition-colors cursor-pointer">
                        Fechar
                    </button>
                </div>

                <div v-if="!selectedContract?.items?.length" class="py-8 text-center text-sm text-[#ccc]">
                    Nenhum item neste contrato.
                </div>

                <div v-else class="border border-[#e8e5df] rounded-lg overflow-hidden">
                    <div class="bg-[#faf9f7] border-b border-[#e8e5df] grid grid-cols-4 px-4 py-2">
                        <span class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium">Serviço</span>
                        <span
                            class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium text-center">Qtd</span>
                        <span class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium text-center">Preço
                            unit.</span>
                        <span
                            class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium text-right">Subtotal</span>
                    </div>
                    <div v-for="item in selectedContract.items" :key="item.id"
                        class="grid grid-cols-4 px-4 py-3 border-b border-[#f0ede8] last:border-b-0 items-center">
                        <p class="text-sm font-medium text-[#1a1917]">{{ item.service?.name }}</p>
                        <p class="text-sm text-[#888] text-center">{{ item.quantity }}</p>
                        <p class="text-sm text-[#888] text-center">R$ {{ Number(item.unit_price).toFixed(2) }}</p>
                        <p class="text-sm font-medium text-[#1a1917] text-right">
                            R$ {{ (item.quantity * item.unit_price).toFixed(2) }}
                        </p>
                    </div>
                </div>

                <div
                    class="flex items-center justify-between mt-3 px-4 py-3 bg-[#faf9f7] rounded-lg border border-[#e8e5df]">
                    <span class="text-xs text-[#aaa] uppercase tracking-widest font-medium">Total</span>
                    <span class="font-serif text-xl italic text-[#1a1917]">
                        R$ {{ Number(selectedContract?.total).toFixed(2) }}
                    </span>
                </div>
            </div>
        </transition>

        <!-- History panel -->
        <transition name="slide">
            <div v-if="historyModal" class="bg-white border border-[#e8e5df] rounded-xl p-5 mb-6">

                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium">
                        Histórico — {{ selectedHistoryContract?.client?.name }}
                    </p>

                    <button @click="historyModal = false"
                        class="text-xs text-[#999] border border-[#e8e5df] px-3 py-1 rounded-md hover:bg-[#faf9f7] transition-colors cursor-pointer">
                        Fechar
                    </button>
                </div>

                <div v-if="histories.length === 0" class="py-8 text-center text-sm text-[#ccc]">
                    Nenhum histórico encontrado.
                </div>

                <div v-else class="border border-[#e8e5df] rounded-lg overflow-hidden">

                    <div class="bg-[#faf9f7] border-b border-[#e8e5df] grid grid-cols-5 px-4 py-2">
                        <span class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium">
                            Ação
                        </span>

                        <span class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium">
                            Campo
                        </span>

                        <span class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium">
                            Valor Antigo
                        </span>

                        <span class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium">
                            Novo Valor
                        </span>

                        <span class="text-[10px] text-[#aaa] uppercase tracking-widest font-medium text-right">
                            Data
                        </span>
                    </div>

                    <div v-for="history in histories" :key="history.id"
                        class="grid grid-cols-5 px-4 py-3 border-b border-[#f0ede8] last:border-b-0 items-center">

                        <p class="text-sm font-medium text-[#1a1917]">
                            {{ historyActionLabel(history.action) }}
                        </p>

                        <p class="text-sm text-[#888]">
                            {{ history.field ?? '—' }}
                        </p>

                        <p class="text-sm text-[#888]">
                            {{ history.old_value ?? '—' }}
                        </p>

                        <p class="text-sm text-[#888]">
                            {{ history.new_value ?? '—' }}
                        </p>

                        <p class="text-sm text-[#888] text-right">
                            {{ formatDate(history.created_at) }}
                        </p>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Contracts table -->
        <div class="bg-white border border-[#e8e5df] rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#faf9f7] border-b border-[#e8e5df]">
                        <th class="text-left text-[10px] text-[#aaa] uppercase tracking-widest font-medium px-4 py-3">
                            Cliente</th>
                        <th class="text-left text-[10px] text-[#aaa] uppercase tracking-widest font-medium px-4 py-3">
                            Período</th>
                        <th class="text-left text-[10px] text-[#aaa] uppercase tracking-widest font-medium px-4 py-3">
                            Status</th>
                        <th class="text-left text-[10px] text-[#aaa] uppercase tracking-widest font-medium px-4 py-3">
                            Total</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="contracts.length === 0">
                        <td colspan="5" class="px-4 py-10 text-center text-sm text-[#ccc]">
                            Nenhum contrato cadastrado ainda.
                        </td>
                    </tr>
                    <tr v-for="c in contracts" :key="c.id"
                        class="border-t border-[#f0ede8] hover:bg-[#faf9f7] transition-colors">
                        <td class="px-4 py-3 font-medium text-[#1a1917]">{{ c.client?.name }}</td>
                        <td class="px-4 py-3 text-[#888]">
                            <span v-if="c.start_date || c.end_date">
                                {{ formatDate(c.start_date) }} → {{ formatDate(c.end_date) }}
                            </span>
                            <span v-else class="text-[#ccc]">—</span>
                        </td>
                        <td class="px-4 py-3">
                            <span :class="statusClass(c.status)">{{ statusLabel(c.status) }}</span>
                        </td>
                        <td class="px-4 py-3 font-medium text-[#1a1917]">R$ {{ Number(c.total).toFixed(2) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2 justify-end">
                                <button @click="viewHistory(c)"
                                    class="text-xs text-[#888] border border-[#e8e5df] px-3 py-1 rounded-md hover:bg-[#f0ede8] hover:text-[#1a1917] transition-colors cursor-pointer">
                                    Histórico
                                </button>
                                <button @click="viewItems(c)"
                                    class="text-xs text-[#888] border border-[#e8e5df] px-3 py-1 rounded-md hover:bg-[#f0ede8] hover:text-[#1a1917] transition-colors cursor-pointer">
                                    Itens
                                </button>
                                <button @click="edit(c)"
                                    class="text-xs text-[#888] border border-[#e8e5df] px-3 py-1 rounded-md hover:bg-[#f0ede8] hover:text-[#1a1917] transition-colors cursor-pointer"
                                    v-if="c.status == 'active'">
                                    Editar
                                </button>
                                <button @click="cancel(c.id)" v-if="c.status !== 'cancelled'"
                                    class="text-xs text-[#92400e] border border-[#fef3c7] px-3 py-1 rounded-md hover:bg-[#fffbeb] transition-colors cursor-pointer">
                                    Cancelar
                                </button>
                                <button @click="remove(c.id)"
                                    class="text-xs text-[#c0392b] border border-[#fdd] px-3 py-1 rounded-md hover:bg-[#fff0f0] transition-colors cursor-pointer">
                                    Excluir
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div v-if="pagination.last_page > 1"
                class="flex items-center justify-between px-4 py-3 border-t border-[#e8e5df] bg-[#faf9f7]">
                <p class="text-xs text-[#aaa]">
                    Mostrando {{ pagination.from }}–{{ pagination.to }} de {{ pagination.total }}
                </p>
                <div class="flex items-center gap-1">
                    <button @click="goToPage(pagination.current_page - 1)" :disabled="!pagination.prev_page_url"
                        class="h-7 w-7 flex items-center justify-center rounded-md border border-[#e8e5df] text-[#888] disabled:opacity-30 disabled:cursor-not-allowed hover:bg-[#f0ede8] transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                    <button v-for="page in pagination.last_page" :key="page" @click="goToPage(page)" :class="[
                        'h-7 w-7 flex items-center justify-center rounded-md text-xs font-medium transition-colors cursor-pointer',
                        page === pagination.current_page
                            ? 'bg-[#1a1917] text-[#f0ede6]'
                            : 'border border-[#e8e5df] text-[#888] hover:bg-[#f0ede8]'
                    ]">
                        {{ page }}
                    </button>
                    <button @click="goToPage(pagination.current_page + 1)" :disabled="!pagination.next_page_url"
                        class="h-7 w-7 flex items-center justify-center rounded-md border border-[#e8e5df] text-[#888] disabled:opacity-30 disabled:cursor-not-allowed hover:bg-[#f0ede8] transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            aria-hidden="true">
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
            clients: [],
            services: [],
            contracts: [],
            items: [],
            existingItems: [],
            showForm: false,
            showItems: false,
            selectedContract: null,
            pagination: {
                current_page: 1,
                last_page: 1,
                from: null,
                to: null,
                total: 0,
                prev_page_url: null,
                next_page_url: null,
            },
            form: { id: null, client_id: "", start_date: "", end_date: "", status: "active" },
            historyModal: false,
            histories: [],
            selectedHistoryContract: null,
        };
    },
    computed: {
        total() {
            return this.items.reduce((sum, i) => sum + i.quantity * i.unit_price, 0);
        },
        existingTotal() {
            return this.existingItems.reduce((sum, i) => sum + i.quantity * Number(i.unit_price), 0);
        },
        grandTotal() {
            return this.total + this.existingTotal;
        },
    },
    mounted() {
        this.load();
    },
    methods: {
        async load(page = 1) {
            const [clients, services, contracts] = await Promise.all([
                api.get("/v1/clients"),
                api.get("/v1/services"),
                api.get("/v1/contracts", { params: { page } }),
            ]);
            this.clients = clients.data.data;
            this.services = services.data.data.map(s => ({ ...s, quantity: 1 }));
            this.contracts = contracts.data.data;
            this.pagination = {
                current_page: contracts.data.current_page,
                last_page: contracts.data.last_page,
                from: contracts.data.from,
                to: contracts.data.to,
                total: contracts.data.total,
                prev_page_url: contracts.data.prev_page_url,
                next_page_url: contracts.data.next_page_url,
            };
        },
        goToPage(page) {
            if (page < 1 || page > this.pagination.last_page) return;
            this.load(page);
        },
        openForm() {
            this.reset();
            this.showItems = false;
            this.historyModal = false;
            this.showForm = true;
        },
        edit(contract) {
            this.form = {
                id: contract.id,
                client_id: contract.client_id,
                start_date: contract.start_date?.split('T')[0] ?? '',
                end_date: contract.end_date?.split('T')[0] ?? '',
                status: contract.status
            };
            this.existingItems = contract.items ? [...contract.items] : [];
            this.items = [];
            this.showItems = false;
            this.historyModal = false;
            this.showForm = true;
        },
        viewItems(contract) {
            this.selectedContract = contract;
            this.showForm = false;
            this.historyModal = false;
            this.showItems = true;
        },
        addItem(service) {
            this.items.push({
                service_id: service.id,
                name: service.name,
                quantity: service.quantity || 1,
                unit_price: service.base_price,
            });
        },
        removeItem(index) {
            this.items.splice(index, 1);
        },
        async removeExistingItem(itemId) {
            await api.delete(`/v1/contract-items/${itemId}`);
            this.existingItems = this.existingItems.filter(i => i.id !== itemId);
        },
        async save() {
            if (this.form.id) {
                const { data } = await api.put(`/v1/contracts/${this.form.id}`, {
                    client_id: this.form.client_id,
                    start_date: this.form.start_date,
                    end_date: this.form.end_date,
                    status: this.form.status
                });
                if (this.items.length > 0) {
                    await api.post(`/v1/contracts/${this.form.id}/items`, {
                        ...this.items,
                    });
                }
            } else {
                const { data } = await api.post("/v1/contracts", {
                    client_id: this.form.client_id,
                    start_date: this.form.start_date,
                    end_date: this.form.end_date,
                    status: this.form.status
                });
                if (this.items.length > 0) {
                    await api.post(`/v1/contracts/${data.id}/items`, {
                        ...this.items,
                    });
                }
            }
            this.reset();
            this.showForm = false;
            this.load(this.pagination.current_page);
        },
        async cancel(id) {
            await api.patch(`/v1/contracts/${id}/cancel`);
            this.load(this.pagination.current_page);
        },
        async remove(id) {
            await api.delete(`/v1/contracts/${id}`);
            this.load(this.pagination.current_page);
        },
        reset() {
            this.form = { id: null, client_id: "", start_date: "", end_date: "", status: "active" };
            this.items = [];
            this.existingItems = [];
        },
        formatDate(d) {
            if (!d) return "—";
            const [datePart] = d.split("T");
            const [y, m, day] = datePart.split("-");
            return `${day}/${m}/${y}`;
        },
        statusLabel(s) {
            return { active: "Ativo", cancelled: "Cancelado" }[s] ?? s;
        },
        statusClass(s) {
            const base = "inline-flex items-center text-[11px] font-medium px-2.5 py-0.5 rounded-full";
            const map = {
                active: `${base} bg-[#f0fdf4] text-[#166534]`,
                cancelled: `${base} bg-[#fffbeb] text-[#92400e]`,
            };
            return map[s] ?? `${base} bg-[#f5f4f0] text-[#888]`;
        },
        async viewHistory(contract) {
            const { data } = await api.get(
                `/v1/contracts/${contract.id}/histories`
            );

            this.histories = data.data ?? data;
            this.selectedHistoryContract = contract;

            this.showItems = false;
            this.showForm = false;
            this.historyModal = true;
        },
        historyActionLabel(action) {
            const map = {
                created: 'Criação',
                updated: 'Atualização',
                cancelled: 'Cancelamento',
            };

            return map[action] ?? action;
        },
    },
};
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: all 0.2s ease;
}

.slide-enter-from,
.slide-leave-to {
    opacity: 0;
    transform: translateY(-6px);
}
</style>
