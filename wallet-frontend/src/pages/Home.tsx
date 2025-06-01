const Home = () => {
	  return (
	<div className="flex flex-col items-center justify-center bg-gray-100 pt-20 pb-20">
	  <h1 className="text-4xl font-bold mb-4">Bienvenido a Epayco Wallet</h1>
	  <p className="text-lg text-gray-700 mb-8">Tu Billetera digital </p>
	  <p className="text-lg text-gray-700 mb-8">Creado por  - Jorge Cortes </p>

	  <a
		href="/register"
		className="px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-300"
	  >
		Inicio
	  </a>
	</div>
  );
}

export default Home;