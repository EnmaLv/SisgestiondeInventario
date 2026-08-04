<div
    x-show="isChatOpen"
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200 transform"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed left-0 top-0 h-[100dvh] w-full sm:w-[400px] bg-white dark:bg-gray-800 shadow-2xl z-50 flex flex-col border-r border-gray-100 dark:border-gray-700 transition-all duration-300"
    style="display: none;"
    x-data="{
        view: 'list',
        selectedContact: null,
        contacts: [],
        messages: [],
        newMessage: '',
        isLoading: false,
        currentEchoChannel: null,

        init() {
            this.fetchContacts();

            if (window.Echo) {
                window.Echo.private('App.Models.User.' + {{ auth()->id() ?? 'null' }})
                    .listen('MessageSent', (e) => {
                        if (!this.selectedContact || this.selectedContact.id != e.sender_id) {
                            let contactIndex = this.contacts.findIndex(c => c.id == e.sender_id);
                            if (contactIndex !== -1) {
                                let contact = this.contacts[contactIndex];
                                contact.lastMessage = e.body;
                                contact.time = e.time;
                                contact.unreadCount += 1;

                                this.contacts.splice(contactIndex, 1);
                                this.contacts.unshift(contact);
                            }
                        }
                    });
            }
        },

        fetchContacts() {
            this.isLoading = true;
            axios.get('/mensajes/contactos/lista')
                .then(response => {
                    this.contacts = response.data;
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },

        selectContact(contact) {
            this.selectedContact = contact;
            contact.unreadCount = 0;
            this.view = 'chat';
            this.messages = [];
            this.fetchMessages();
        },

        fetchMessages() {
            this.isLoading = true;
            if (this.currentEchoChannel) window.Echo.leave(this.currentEchoChannel);

            axios.get(`/mensajes/${this.selectedContact.id}`)
                .then(response => {
                    this.messages = response.data.messages;
                    const convId = response.data.conversation_id;
                    this.scrollToBottom();

                    this.currentEchoChannel = 'chat.' + convId;
                    if (window.Echo) {
                        window.Echo.private(this.currentEchoChannel)
                            .listen('MessageSent', (e) => {
                                if (e.sender_id != {{ auth()->id() ?? 'null' }}) {
                                    this.messages.push({ id: e.id, body: e.body, is_mine: false, time: e.time });
                                    this.scrollToBottom();

                                    let contactIndex = this.contacts.findIndex(c => c.id === this.selectedContact.id);
                                    if(contactIndex !== -1) {
                                        let c = this.contacts[contactIndex];
                                        c.lastMessage = e.body;
                                        c.time = e.time;
                                        c.unreadCount = 0;
                                        this.contacts.splice(contactIndex, 1);
                                        this.contacts.unshift(c);
                                    }
                                }
                            });
                    }
                }).finally(() => { this.isLoading = false; });
        },

        sendMessage() {
            if (!this.newMessage.trim() || !this.selectedContact) return;
            let text = this.newMessage;
            this.newMessage = '';

            axios.post(`/mensajes/${this.selectedContact.id}`, { body: text })
                .then(response => {
                    this.messages.push(response.data);
                    this.messages = [...this.messages]; // Fuerza la reactividad en Alpine
                    this.scrollToBottom();

                    let contactIndex = this.contacts.findIndex(c => c.id === this.selectedContact.id);
                    if(contactIndex !== -1) {
                        let c = this.contacts[contactIndex];
                        c.lastMessage = text;
                        c.time = 'Ahora';
                        this.contacts.splice(contactIndex, 1);
                        this.contacts.unshift(c);
                    }
                });
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = document.getElementById('sidebar-messages-container');
                if (container) container.scrollTop = container.scrollHeight;
            });
        }
    }"
>
    <!-- Header -->
    <div class="px-6 py-5 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800 sticky top-0 z-10">
        <div class="flex items-center gap-3">
            <template x-if="view === 'chat'">
                <button @click="view = 'list'" class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
            </template>
            <h3 class="font-extrabold text-2xl text-gray-900 dark:text-white tracking-tight" x-text="view === 'list' ? 'Mensajes' : selectedContact.name">Mensajes</h3>
        </div>
        <div class="flex items-center gap-1">
            <!-- Botón Ver Mensajería Completa (Solo Psicólogos/Admin en Desktop) -->
            @if(auth()->user()->role !== 'paciente')
                <a href="#" title="Ver mensajería completa" class="hidden sm:inline-flex p-2 hover:bg-blue-50 dark:hover:bg-blue-900/50 rounded-full transition text-gray-400 dark:text-gray-500 hover:text-blue-600 dark:hover:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                </a>
            @endif

            <button @click="isChatOpen = false" class="p-1.5 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-full transition text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 ml-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>

    <!-- Content Area -->
    <div class="flex-1 overflow-y-auto bg-white dark:bg-gray-800 no-scrollbar">

        <!-- List View -->
        <div x-show="view === 'list'" class="divide-y divide-gray-50 dark:divide-gray-700 h-full flex flex-col">
            <!-- Search -->
            <div class="px-5 py-4 bg-white dark:bg-gray-800">
                <div class="relative">
                    <input type="text" placeholder="Buscar pacientes..." class="w-full bg-gray-100 dark:bg-gray-700 border-none rounded-2xl py-2.5 px-11 text-sm focus:ring-2 focus:ring-blue-500/20 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white transition">
                    <svg class="w-5 h-5 absolute left-4 top-2.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto no-scrollbar">
                <div class="px-5 py-2 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Recientes</div>

                <!-- Contacts Loop -->
                <template x-for="contact in contacts" :key="contact.id">
                    <div
                        @click="selectContact(contact)"
                        class="px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer flex items-center gap-4 transition group"
                    >
                        <div class="relative flex-shrink-0">
                            <template x-if="contact.profile_photo">
                                <div class="w-14 h-14 rounded-full overflow-hidden bg-gradient-to-tr from-blue-600 to-indigo-700 shadow-md group-hover:scale-105 transition transform">
                                    <img :src="contact.profile_photo" alt="Foto de perfil" class="w-full h-full object-cover">
                                </div>
                            </template>
                            <template x-if="!contact.profile_photo">
                                <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-700 flex items-center justify-center text-white font-bold text-lg shadow-md group-hover:scale-105 transition transform" x-text="contact.avatar"></div>
                            </template>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline mb-1">
                                <h4 class="font-bold text-gray-900 dark:text-white truncate text-base" x-text="contact.name"></h4>
                                <div x-show="contact.unreadCount > 0" class="w-2.5 h-2.5 bg-blue-600 rounded-full shrink-0"></div>
                            </div>
                            <p class="text-sm truncate leading-relaxed" :class="contact.unreadCount > 0 ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400'" x-text="contact.lastMessage"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Individual Chat View -->
        <div x-show="view === 'chat'" class="h-full flex flex-col bg-gray-50 dark:bg-gray-900" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4">
            <div id="sidebar-messages-container" class="flex-1 p-6 flex flex-col gap-4 overflow-y-auto no-scrollbar bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-fixed dark:bg-gray-900" style="scroll-behavior: smooth;">

                <div x-show="isLoading" class="text-center text-gray-400 dark:text-gray-500 text-xs py-2">Cargando...</div>

                <template x-for="msg in messages" :key="msg.id">
                    <div class="w-full flex flex-col">
                        <!-- Recibido -->
                        <template x-if="!msg.is_mine">
                            <div class="flex flex-col gap-1 self-start max-w-[85%]">
                                <div class="px-4 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-800 dark:text-gray-200 rounded-2xl rounded-tl-sm shadow-sm text-[14px] w-fit">
                                    <span x-text="msg.body"></span>
                                </div>
                                <span class="text-[10px] text-gray-400 dark:text-gray-500 ml-1 font-medium" x-text="msg.time"></span>
                            </div>
                        </template>

                        <!-- Enviado -->
                        <template x-if="msg.is_mine">
                            <div class="flex flex-col gap-1 self-end max-w-[85%] ml-auto items-end">
                                <div class="px-4 py-3 bg-blue-600 text-white rounded-2xl rounded-tr-sm shadow-md text-[14px] w-fit">
                                    <span x-text="msg.body"></span>
                                </div>
                                <span class="text-[10px] text-gray-400 dark:text-gray-500 mr-1 font-medium" x-text="msg.time"></span>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <!-- Input Area -->
            <div class="p-5 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="flex-1 relative">
                    <input type="text" x-model="newMessage" @keydown.enter="sendMessage" placeholder="Escribe un mensaje..." class="w-full bg-gray-100 dark:bg-gray-700 border-none rounded-2xl py-3 px-5 text-sm focus:ring-2 focus:ring-blue-500/20 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white transition">
                </div>
                <button @click="sendMessage" class="p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5 active:translate-y-0" :class="newMessage.trim() === '' ? 'opacity-50 cursor-not-allowed' : ''">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 10px;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #374151;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #d1d5db;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #4b5563;
    }
    .no-scrollbar::-webkit-scrollbar {
        display: none !important;
    }
    .no-scrollbar {
        -ms-overflow-style: none !important;
        scrollbar-width: none !important;
    }
</style>
