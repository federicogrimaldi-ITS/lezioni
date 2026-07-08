package negozio.demo.service;

import java.util.List;

import org.springframework.stereotype.Service;

import negozio.demo.entity.ProdottoOrtofrutticolo;
import negozio.demo.repository.ProdottoRepository;

@Service
public class ProdottoService {

	private final ProdottoRepository prodottoRepository;

	public ProdottoService(ProdottoRepository prodottoRepository) {
		this.prodottoRepository = prodottoRepository;
	}

	public List<ProdottoOrtofrutticolo> getAllProdotti() {
		return prodottoRepository.findAll();
	}
}