import { useState } from "react";
import { payWallet } from "../api/wallet";


const Pay = () => {
	const [form, setForm] = useState({
			document: '',
			celPhone: '',
			amount: 0,
			description: '',
		});
		const [message, setMessage] = useState('');
		const [errorStyle, setErrorStyle] = useState('');
		const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
			event.preventDefault();
			setForm({
				...form,
				[event.target.name]: event.target.value
			});
		};
		const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
			event.preventDefault();
			try {
				const response = await payWallet({
					document: form.document,
					celPhone: form.celPhone,
					amount: parseFloat(form.amount.toString()),
					description: form.description,
				});
				
				setMessage(response.message);
				setErrorStyle('bg-green-100 text-green-800 border-green-400');
			
				setForm({
					document: '',
					celPhone: '',
					amount: 0,
					description: '',
				});
				setTimeout(() => {
					setMessage('')
				}, 2000);
			} catch (error) {
				
				setMessage(error.message);
				setErrorStyle('bg-red-100 text-red-800 border-red-400');
				setTimeout(() => {
					setMessage('');
				}, 3000);s
			}
		};
	
	return (
		<div className="max-w-md mx-auto p-6 bg-white shadow-md rounded-lg mt-32">
			<h2 className="text-2xl font-bold mb-4">Comprar</h2>
			{message && <p className={` ${errorStyle}  h-auto  mt-4 rounded-lg p-2 mb-4 text-center` } >{message}</p>}
			<form className="space-y-4" onSubmit={handleSubmit}>
				<div className="mb-4">
					<label className="block text-sm font-medium text-gray-700">Documento</label>
					<input 
						type="text"
						name="document"
						value={form.document}
						onChange={handleChange}
						className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:content-none" />
				</div>
				<div className="mb-4">
					<label className="block text-sm font-medium text-gray-700">Teléfono</label>
					<input 
						type="tel" 
						name="celPhone"
						value={form.celPhone}
						onChange={handleChange}
						className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:content-none" />
				</div>
				<div className="mb-4">
					<label htmlFor="amount" className="block text-sm font-medium text-gray-700 mb-2">
						Descripcion
					</label>
					<input
						type="text"
						value={form.description}
						onChange={handleChange}
						name="description"
						
						className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:content-none"
					/>
				</div>
				<div className="mb-4">
					<label htmlFor="amount" className="block text-sm font-medium text-gray-700 mb-2">
						Monto Compra:
					</label>
					<input
						type="number"
						value={form.amount}
						onChange={handleChange}
						name="amount"
						placeholder="$0.00"
						className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:content-none"
					/>
				</div>
				
				<button
				type="submit"
				className="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-200"
				>
					Recargar
				</button>
			</form>
		</div>
	);
  	
}

export default Pay;