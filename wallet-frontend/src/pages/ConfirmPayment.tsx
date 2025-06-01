import {   useState } from "react";
import { useParams } from "react-router-dom";
import { confirmPayment } from "../api/wallet";

const ConfirmPayment = () => {

	const {sessionId} = useParams();
	const [token, setToken] = useState('');

	const [message, setMessage] = useState('');
	const [errorStyle, setErrorStyle] = useState('');
	
	const handleConfirmPayment = async () => {
		let flagError = false;

		let errMsg = '';
		if (!sessionId) {
			errMsg = "No se encontró el sessionId.\n";
			flagError = true;
			
		}
		if (!token) {
			errMsg ="Por favor, ingresa tu token.";
			flagError = true;
		}


		if (flagError) {
			setMessage(errMsg);
			setErrorStyle('bg-red-100 text-red-800 border-red-400');
			return;
		}
		setMessage('Procesando tu pago...');
		setErrorStyle('bg-blue-100 text-blue-800 border-blue-400');

		try {
			const response = await confirmPayment({
				sessionId: sessionId,
				token: token
			});
		
			if (!response.success) {
				setMessage(response.message);
				setErrorStyle('bg-red-100 text-red-800 border-red-400');
				setTimeout(() => {
					setToken('');
					setMessage('');
				}, 2000);
				return false;
			}

			setMessage(response.message);
			setErrorStyle('bg-green-100 text-green-800 border-green-400');
			setToken('');
			setTimeout(() => {
				setMessage('');
			}, 2000);
		
		} catch (error) {
			setMessage(error.message);
			setErrorStyle('bg-red-100 text-red-800 border-red-400');
			setTimeout(() => {
				setToken('');
				setMessage('');
			}, 4000);
		}
	};

	return (
		<div className="max-w-md mx-auto p-6 bg-white shadow-md rounded-lg mt-32">
			<h2 className="text-2xl font-bold mb-4">Confirmar Comprar</h2>
			<p className="text-lg mb-6">Por favor, confirma tu pago.</p>

			{message && <p className={` ${errorStyle}  h-auto  mt-4 rounded-lg p-2 mb-4 text-center` } >{message}</p>}

			<div className="mb-4">
				<label className="block text-sm font-medium text-gray-700">Ingresa tu token</label>
				<input 
					type="text"
					name="document"
					value={token}
					onChange={(e) => setToken(e.target.value)}
				
					className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:content-none" />
			</div>

			
			<button className="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mx-auto"
			onClick={handleConfirmPayment}>
				Confirmar
			</button>
		</div>
  );
}

export default ConfirmPayment;