package negozio.demo.entity;

import java.math.BigDecimal;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.Table;

@Entity
@Table(name = "prodotti_ortofrutticoli")
public class ProdottoOrtofrutticolo {

	@Id
	@GeneratedValue(strategy = GenerationType.IDENTITY)
	private Long id;

	@Column(nullable = false)
	private String nome;

	@Column(nullable = false)
	private String categoria;

	@Column(nullable = false)
	private String origine;

	@Column(name = "prezzo_kg", nullable = false)
	private BigDecimal prezzoKg;

	@Column(nullable = false)
	private String disponibilita;

	public ProdottoOrtofrutticolo() {
	}

	public Long getId() {
		return id;
	}

	public void setId(Long id) {
		this.id = id;
	}

	public String getNome() {
		return nome;
	}

	public void setNome(String nome) {
		this.nome = nome;
	}

	public String getCategoria() {
		return categoria;
	}

	public void setCategoria(String categoria) {
		this.categoria = categoria;
	}

	public String getOrigine() {
		return origine;
	}

	public void setOrigine(String origine) {
		this.origine = origine;
	}

	public BigDecimal getPrezzoKg() {
		return prezzoKg;
	}

	public void setPrezzoKg(BigDecimal prezzoKg) {
		this.prezzoKg = prezzoKg;
	}

	public String getDisponibilita() {
		return disponibilita;
	}

	public void setDisponibilita(String disponibilita) {
		this.disponibilita = disponibilita;
	}
}