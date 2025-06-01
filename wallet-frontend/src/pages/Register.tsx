import { useState } from "react";
import { createClient } from "../api/wallet";



const Register = ()=> {

	const [form, setForm] = useState({
		firstName: '',
		lastName: '',
		email: '',
		document: '',
		celPhone: ''
	});

	const [message, setMessage] = useState('');
	
	const [errorStyle, setErrorStyle] = useState('');


	const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
		event.preventDefault();
		setForm({
			...form,
			[event.target.name]: event.target.value
		});	
	}


	const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
		event.preventDefault();
		try {
			 const response = await createClient(form);
			 setMessage(response.message);
			setMessage("Cliente registrado exitosamente.");
			setErrorStyle('bg-green-100 text-green-800 border-green-400');

			
			setTimeout(() => {
				setForm({
					firstName: '',
					lastName: '',
					email: '',
					document: '',
					celPhone: ''
				});
				setMessage('')
			}, 2000);
		} catch (error) {
			setMessage(error.message);
			setErrorStyle('bg-red-100 text-red-800 border-red-400');
			setTimeout(() => {
				setForm({
					firstName: '',
					lastName: '',
					email: '',
					document: '',
					celPhone: ''
				});
				setMessage('');
			}, 3000);
		}
	};


	return (
		<div className="max-w-md mx-auto p-6 bg-white shadow-md rounded-lg mt-32">
			<h2 className="text-2xl font-bold mb-4">Registro de Cliente</h2>
			{message && <p className={` ${errorStyle}  h-auto  mt-4 rounded-lg p-2 mb-4 text-center` } >{message}</p>}
			<form onSubmit={handleSubmit} className="space-y-4">
				<div className="mb-4">
					<label className="block text-sm font-medium text-gray-700">Nombre</label>
					<input 
						type="text" 
						name="firstName"
						value={form.firstName}
						onChange={handleChange}

						className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:content-none" 
					/>
				</div>
				<div className="mb-4">
					<label className="block text-sm font-medium text-gray-700">Apellido</label>
					<input
						type="text" 
						name="lastName"
						value={form.lastName}
						onChange={handleChange}
						className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:content-none" 
						/>
				</div>
				<div className="mb-4">
					<label className="block text-sm font-medium text-gray-700">Email</label>
					<input 
						type="email" 
						name="email"
						value={form.email}
						onChange={handleChange}
						className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:content-none" />
				</div>
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
				<button 
					type="submit" 
					className="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition duration-200">
						Registrar
				</button>
			</form>
		</div>
	);
}

export default Register