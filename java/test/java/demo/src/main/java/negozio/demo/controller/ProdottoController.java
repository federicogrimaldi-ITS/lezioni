package negozio.demo.controller;

import java.util.List;

import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import negozio.demo.entity.ProdottoOrtofrutticolo;
import negozio.demo.service.ProdottoService;

@RestController
@RequestMapping("/api/prodotti")
public class ProdottoController {

	private final ProdottoService prodottoService;

	public ProdottoController(ProdottoService prodottoService) {
		this.prodottoService = prodottoService;
	}

	@GetMapping
	public List<ProdottoOrtofrutticolo> getProdotti() {
		return prodottoService.getAllProdotti();
	}
}