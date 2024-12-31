<div>
    <x-heroicon-m-sun class="w-10 h-10 m-3 text-yellow-500" x-show="darkMode"
        @click="darkMode = !darkMode"
    />
    <x-heroicon-o-moon class="w-10 h-10 m-3 text-yellow-500" x-show="!darkMode"
        @click="darkMode = !darkMode"
    />
</div>
