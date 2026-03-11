<!-- Top Navigation Bar -->
<header
	class="sticky top-0 z-50 w-full border-b border-solid border-border-dark bg-background-dark/80 backdrop-blur-md px-6 lg:px-20 py-4">
	<div class="max-w-[1440px] mx-auto flex items-center justify-between gap-8">
		<div class="flex items-center gap-12">
			<!-- Logo -->
			<div class="flex items-center gap-3 text-primary">
				<div class="size-8 flex items-center justify-center">
					<span class="material-symbols-outlined text-3xl">terminal</span>
				</div>
				<h2 class="text-slate-100 text-2xl font-bold leading-tight tracking-tight">TechStore</h2>
			</div>
			<!-- Nav Links -->
			<nav class="hidden lg:flex items-center gap-8">
				<a class="text-slate-300 hover:text-primary transition-colors text-sm font-medium" href="#">Laptops</a>
				<a class="text-slate-300 hover:text-primary transition-colors text-sm font-medium" href="#">Desktops</a>
				<a class="text-slate-300 hover:text-primary transition-colors text-sm font-medium"
					href="#">Components</a>
				<a class="text-slate-300 hover:text-primary transition-colors text-sm font-medium" href="#">Gaming</a>
			</nav>
		</div>
		<!-- Search and Actions -->
		<div class="flex flex-1 justify-end items-center gap-6">
			<label class="hidden md:flex flex-col min-w-48 max-w-sm w-full">
				<div
					class="flex items-center rounded-lg bg-surface-dark border border-border-dark focus-within:border-primary px-4 py-2 transition-all">
					<span class="material-symbols-outlined text-slate-400 text-xl">search</span>
					<input
						class="w-full border-none bg-transparent text-slate-100 focus:ring-0 placeholder:text-slate-500 text-sm ml-2"
						placeholder="Search premium hardware..." value="" />
				</div>
			</label>
			<div class="flex gap-3">
				<a href='/pages/login.php'
					class="flex items-center justify-center rounded-lg h-10 w-10 bg-surface-dark border border-border-dark text-slate-100 hover:bg-primary/20 transition-colors">
					<span class="material-symbols-outlined">person</span>
				</a>
				<button
					class="flex items-center justify-center rounded-lg h-10 w-10 bg-surface-dark border border-border-dark text-slate-100 hover:bg-primary/20 transition-colors relative">
					<span class="material-symbols-outlined">shopping_cart</span>
					<span
						class="absolute -top-1 -right-1 bg-primary text-[10px] font-bold px-1.5 rounded-full border-2 border-background-dark">3</span>
				</button>
			</div>
			<div
				class="h-10 w-10 rounded-full bg-primary/20 border border-primary/40 flex items-center justify-center overflow-hidden">
				<img alt="User Profile" class="w-full h-full object-cover"
					data-alt="Modern user profile avatar circular"
					src="https://lh3.googleusercontent.com/aida-public/AB6AXuCe7tnKfQuh_Astb86U1NeWdLZz2tiXS7HuyKftVM1rSfKEjdxiY7RjXV0EnTDyxhaei3XKRaSX5HDKj8XGQJMcPvHic7FvURo9omcFDbrB588YsUHcaERZstnNcgB0EqnxN1HWo8mZw55Knd7P5WZY2RZndiThtiC4OylMpUKqX8gwDjjw-hFPV7wIiSqwu3AvNKq9sj5x0-3NbJL3vujeU8leArxJ165y9uKaqcLxGphjzbBLsbTGNd77IUnzsAi2fP3-41DtCrM" />
			</div>
		</div>
	</div>
</header>