<div class="drawer-side z-10">
    <label for="my-drawer-2" class="drawer-overlay"></label>
    <ul
        class="menu w-64 h-full bg-gradient-to-b from-blue-300 via-blue-600 to-blue-900 text-xs font-normal text-white block px-5 py-6 no-scrollbar overflow-y-scroll">
        <!-- Sidebar content here -->
        <li>
            <a href="/dashboard" class="rounded-md py-3 @if (Request::is('dashboard')) bg-blue-500 hover:bg-blue-700 mr-2 @endif">
                <span class="material-symbols-outlined mr-2">
                    dashboard
                </span>
                Dashboard
            </a>
        </li>
        <li>
            <a href="/withdraw" class="rounded-md py-3 @if (Request::is('withdraw')) bg-blue-500 hover:bg-blue-700 mr-2 @endif">
                <span class="material-symbols-outlined mr-2">
                    payments
                </span>
                Withdraw
            </a>
        </li>
        <li>
            <a href="/deposit" class="rounded-md py-3 @if (Request::is('deposit')) bg-blue-500 hover:bg-blue-700 mr-2 @endif">
                <span class="material-symbols-outlined mr-2">
                    account_balance
                </span>
                Deposit
            </a>
        </li>
        <li>
            <a href="/history" class="rounded-md py-3 @if (Request::is('history')) bg-blue-500 hover:bg-blue-700 mr-2 @endif">
                <span class="material-symbols-outlined mr-2">
                    history
                </span>
                History Transaction
            </a>
        </li>
    </ul>
</div>
