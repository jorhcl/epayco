import type {ReactNode} from 'react';
import {Link } from 'react-router-dom';


export default function MainLayout({ children }: { children: ReactNode }) {

	return (
		<div className="min-h-screen flex flex-col bg-gray-100">
			<header className="bg-gray-200 shadow p-4 ">
				<div className="container mx-auto flex justify-between items-center gap-4 ">
					<Link to="/">Inicio</Link>
					<Link to="/register">Registro Cliente</Link>
					<Link to="/recharge">Recargar Billetera</Link>
					<Link to="/pay">Iniciar Compra</Link>
					
					<Link to="/balance">Saldo</Link>
				</div>
			</header>

			<main className="flex-grow container mx-auto p-4">
				{children}
			</main>

			<footer className="bg-gray-800 text-white p-4 text-center">
				<p>&copy; {new Date().getFullYear()} Epayco Wallet - Jorge Cortes.</p>
			</footer>
		</div>
	)
}
